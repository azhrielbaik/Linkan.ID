// Platform Admin Notifications Scripts using Server-Sent Events (SSE)

let platformNotifsData = {
    unread_count: 0,
    notifications: []
};

let currentPlatformNotifFilter = 'all';
window.platformEventSource = null;

function togglePlatformNotif(event) {
    if (event) {
        event.stopPropagation();
    }
    const dropdown = document.getElementById('notifDropdown');
    const btn = document.getElementById('notifBtn');
    if (!dropdown) return;

    const isOpen = dropdown.classList.contains('show');
    if (isOpen) {
        dropdown.classList.remove('show');
        if (btn) btn.classList.remove('active');
    } else {
        // Close profile dropdown if open
        const profileDropdown = document.getElementById('profileDropdown');
        if (profileDropdown) profileDropdown.classList.remove('show');

        dropdown.classList.add('show');
        if (btn) btn.classList.add('active');
        // Because SSE updates the data continuously, we don't need to manually fetch here.
        // But we can re-render to ensure it's up to date.
        renderPlatformNotifs(currentPlatformNotifFilter);
    }
}

function updatePlatformUI(data) {
    if (!data) return;
    platformNotifsData = data;
    const badge = document.getElementById('notifBadge');
    const totalPill = document.getElementById('notifTotal');

    // Update badge counter
    const count = data.unread_count || 0;
    if (badge) {
        if (count > 0) {
            badge.innerText = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    if (totalPill) {
        totalPill.innerText = count + ' Baru';
    }

    renderPlatformNotifs(currentPlatformNotifFilter);
}

function getPlatformStreamEndpoint() {
    // Gunakan rute SSE
    return '/platformadmin/notifications/stream';
}

function startPlatformRealtimeSSE() {
    if (window.platformEventSource) {
        window.platformEventSource.close();
    }
    
    window.platformEventSource = new EventSource(getPlatformStreamEndpoint());

    window.platformEventSource.addEventListener('notifications', function(e) {
        try {
            const data = JSON.parse(e.data);
            updatePlatformUI(data);
        } catch (err) {
            console.error('Error parsing SSE notifications:', err);
        }
    });

    window.platformEventSource.onerror = function(e) {
        console.warn('SSE connection lost. Browser will try to reconnect automatically.');
        const listContainer = document.getElementById('notifList');
        // Show offline indicator only if we have no existing data
        if (listContainer && (!platformNotifsData.notifications || platformNotifsData.notifications.length === 0)) {
            listContainer.innerHTML = `
                <div class="notif-empty">
                    <i class="fas fa-wifi" style="color: #f59e0b;"></i>
                    <p>Menghubungkan ulang ke server...</p>
                </div>
            `;
        }
    };
}

function filterPlatformNotif(type, buttonElem) {
    currentPlatformNotifFilter = type;
    
    // Update active tab class
    const tabs = document.querySelectorAll('.notif-filter-tabs .notif-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    if (buttonElem) {
        buttonElem.classList.add('active');
    }

    renderPlatformNotifs(type);
}

function renderPlatformNotifs(filterType) {
    const listContainer = document.getElementById('notifList');
    if (!listContainer) return;

    let items = platformNotifsData.notifications || [];
    if (filterType && filterType !== 'all') {
        items = items.filter(item => item.type === filterType);
    }

    if (items.length === 0) {
        listContainer.innerHTML = `
            <div class="notif-empty">
                <i class="far fa-bell-slash"></i>
                <p>Tidak ada notifikasi ${filterType !== 'all' ? 'untuk kategori ini' : 'baru'}.</p>
            </div>
        `;
        return;
    }

    let html = '';
    items.forEach(item => {
        html += `
            <a href="${item.url}" class="notif-item">
                <div class="notif-icon-box" style="background-color: ${item.icon_bg}; color: ${item.icon_color};">
                    <i class="${item.icon}"></i>
                </div>
                <div class="notif-body">
                    <div class="notif-item-top">
                        <div class="notif-item-title">${item.title}</div>
                        <span class="notif-tag ${item.badge_class}">${item.badge}</span>
                    </div>
                    <div class="notif-item-msg">${item.message}</div>
                    <div class="notif-item-time">
                        <i class="far fa-clock"></i> ${item.time_ago}
                    </div>
                </div>
            </a>
        `;
    });

    listContainer.innerHTML = html;
}

// Global click-outside listener
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notifDropdown');
    const btn = document.getElementById('notifBtn');
    if (!dropdown) return;

    if (!dropdown.contains(event.target) && (!btn || !btn.contains(event.target))) {
        dropdown.classList.remove('show');
        if (btn) btn.classList.remove('active');
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const dropdown = document.getElementById('notifDropdown');
        const btn = document.getElementById('notifBtn');
        if (dropdown) dropdown.classList.remove('show');
        if (btn) btn.classList.remove('active');
    }
});

// Start the SSE connection when page loads
function initPlatformNotifs() {
    startPlatformRealtimeSSE();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPlatformNotifs);
} else {
    initPlatformNotifs();
}

window.addEventListener('beforeunload', function() {
    if (window.platformEventSource) {
        window.platformEventSource.close();
    }
});
