// Platform Admin Notifications Scripts using Server-Sent Events (SSE)

let platformNotifsData = {
    unread_count: 0,
    notifications: []
};

let currentPlatformNotifFilter = 'all';

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

function startPlatformRealtimeSSE() {
    if (typeof Echo === 'undefined') {
        console.warn('Laravel Echo is not loaded. Ensure resources/js/app.js is compiled and included.');
        return;
    }

    Echo.private('admin-notifications')
        .listen('.notifications', function(e) {
            try {
                // Since broadcastWith returns the array directly, the event payload is the array itself.
                // However, Echo wraps it in the event object properties. 
                // We just pass 'e' directly as it should contain unread_count and notifications.
                updatePlatformUI(e);
            } catch (err) {
                console.error('Error handling WebSocket notification:', err);
            }
        });
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
        let messageHtml = '';
        if (item.type === 'product') {
            messageHtml = `Seller <strong>${item.seller_name}</strong> mengajukan produk <em>"${item.product_name}"</em>.`;
        } else if (item.type === 'payout') {
            messageHtml = `Seller <strong>${item.seller_name}</strong> mengajukan withdraw <strong>Rp ${item.amount}</strong> via ${item.bank}.`;
        } else if (item.type === 'appeal') {
            messageHtml = `Seller <strong>${item.seller_name}</strong> mengajukan banding penangguhan akun.`;
        } else {
            messageHtml = item.message || '';
        }

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
                    <div class="notif-item-msg">${messageHtml}</div>
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
    if (typeof Echo !== 'undefined') {
        Echo.leave('admin-notifications');
    }
});
