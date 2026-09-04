// Seller Admin Notifications Scripts using Server-Sent Events (SSE)

let sellerNotifsData = {
    unread_count: 0,
    notifications: []
};

let currentSellerNotifFilter = 'all';

function toggleSellerNotif(event) {
    if (event) {
        event.stopPropagation();
    }
    const dropdown = document.getElementById('sellerNotifDropdown');
    const btn = document.getElementById('sellerNotifBtn');
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
        renderSellerNotifs(currentSellerNotifFilter);
    }
}

function updateSellerUI(data) {
    if (!data) return;
    sellerNotifsData = data;
    const badge = document.getElementById('sellerNotifBadge');
    const totalPill = document.getElementById('sellerNotifTotal');

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

    renderSellerNotifs(currentSellerNotifFilter);
}

function startSellerRealtimeSSE() {
    if (typeof Echo === 'undefined' || !window.Laravel || !window.Laravel.userId) {
        console.warn('Laravel Echo is not loaded or user ID is missing.');
        return;
    }

    Echo.private('seller-notifications.' + window.Laravel.userId)
        .listen('.notifications', function(e) {
            try {
                updateSellerUI(e);
            } catch (err) {
                console.error('Error handling WebSocket notification:', err);
            }
        });
}

function filterSellerNotif(type, buttonElem) {
    currentSellerNotifFilter = type;
    
    // Update active tab class
    const tabs = document.querySelectorAll('.seller-notif-filter-tabs .seller-notif-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    if (buttonElem) {
        buttonElem.classList.add('active');
    }

    renderSellerNotifs(type);
}

function renderSellerNotifs(filterType) {
    const listContainer = document.getElementById('sellerNotifList');
    if (!listContainer) return;

    let items = sellerNotifsData.notifications || [];
    if (filterType && filterType !== 'all') {
        items = items.filter(item => item.type === filterType);
    }

    if (items.length === 0) {
        listContainer.innerHTML = `
            <div class="seller-notif-empty">
                <i class="far fa-bell-slash"></i>
                <p>Tidak ada notifikasi ${filterType !== 'all' ? 'untuk kategori ini' : 'baru'}.</p>
            </div>
        `;
        return;
    }

    let html = '';
    items.forEach(item => {
        html += `
            <a href="${item.url}" class="seller-notif-item">
                <div class="seller-notif-icon-box" style="background-color: ${item.icon_bg}; color: ${item.icon_color};">
                    <i class="${item.icon}"></i>
                </div>
                <div class="seller-notif-body">
                    <div class="seller-notif-item-top">
                        <div class="seller-notif-item-title">${item.title}</div>
                        <span class="seller-notif-tag ${item.badge_class}">${item.badge}</span>
                    </div>
                    <div class="seller-notif-item-msg">${item.message}</div>
                    <div class="seller-notif-item-time">
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
    const dropdown = document.getElementById('sellerNotifDropdown');
    const btn = document.getElementById('sellerNotifBtn');
    if (!dropdown) return;

    if (!dropdown.contains(event.target) && (!btn || !btn.contains(event.target))) {
        dropdown.classList.remove('show');
        if (btn) btn.classList.remove('active');
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const dropdown = document.getElementById('sellerNotifDropdown');
        const btn = document.getElementById('sellerNotifBtn');
        if (dropdown) dropdown.classList.remove('show');
        if (btn) btn.classList.remove('active');
    }
});

// Start the SSE connection when page loads
function initSellerNotifs() {
    startSellerRealtimeSSE();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSellerNotifs);
} else {
    initSellerNotifs();
}

window.addEventListener('beforeunload', function() {
    if (typeof Echo !== 'undefined' && window.Laravel && window.Laravel.userId) {
        Echo.leave('seller-notifications.' + window.Laravel.userId);
    }
});
