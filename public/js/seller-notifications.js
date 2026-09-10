// seller-notifications.js (Cleaned up for new UI)

function toggleSellerNotif(event) {
    if (event) {
        event.stopPropagation();
    }
    const dropdown = document.getElementById("sellerNotifDropdown");
    const btn = document.getElementById("sellerNotifBtn");
    if (!dropdown) return;

    const isOpen = dropdown.classList.contains("show");
    if (isOpen) {
        dropdown.classList.remove("show");
        if (btn) btn.classList.remove("active");
    } else {
        // Close profile dropdown if open
        const profileDropdown = document.getElementById("profileDropdown");
        if (profileDropdown) profileDropdown.classList.remove("show");

        dropdown.classList.add("show");
        if (btn) btn.classList.add("active");
        
        // Hide badge when opened, indicating they've been seen
        const badge = document.getElementById("sellerNotifBadge");
        if (badge) badge.style.display = 'none';
        
        const headerBadge = document.querySelector(".seller-notif-badge-header");
        if (headerBadge) headerBadge.innerText = '00 Notifications';
        
        // Silently mark all as read in the background so it doesn't reappear on reload
        if (typeof markAllSellerNotifsRead === 'function') {
            markAllSellerNotifsRead(null, true);
        }
    }
}

function markSellerNotifRead(event, notificationKey, element) {
    if (event) event.preventDefault();
    
    if (element) {
        element.style.opacity = '0.5';
        element.style.pointerEvents = 'none';
    }
    
    const targetHref = event.currentTarget.href;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        if(targetHref && targetHref !== '#') window.location.href = targetHref;
        return;
    }

    fetch('/admin/notifications/read', {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken.content,
            Accept: "application/json",
            "Content-Type": "application/x-www-form-urlencoded",
        },
        silent: true,
        body: new URLSearchParams({ notification_key: notificationKey }),
    }).finally(() => {
        if(targetHref && targetHref !== '#') window.location.href = targetHref;
    });
}

function markAllSellerNotifsRead(event, silent = false, element = null) {
    if(event) event.stopPropagation();
    
    if (element) {
        const originalText = element.innerText;
        element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        element.style.pointerEvents = 'none';
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return;

    fetch('/admin/notifications/read-all', {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken.content,
            Accept: "application/json",
        },
        silent: true,
    }).finally(() => {
        if (!silent) {
            const badge = document.getElementById("sellerNotifBadge");
            if (badge) badge.style.display = 'none';
            const headerBadge = document.querySelector(".seller-notif-badge-header");
            if (headerBadge) headerBadge.innerText = '00 Notifications';
        }
        
        if (element) {
            element.innerHTML = '<i class="fas fa-check"></i> Selesai';
            setTimeout(() => {
                element.innerText = 'Read All Messages';
                element.style.pointerEvents = 'auto';
            }, 2000);
        }
    });
}

// Global click-outside listener
document.addEventListener("click", function (event) {
    const dropdown = document.getElementById("sellerNotifDropdown");
    const btn = document.getElementById("sellerNotifBtn");
    if (!dropdown) return;

    if (
        !dropdown.contains(event.target) &&
        (!btn || !btn.contains(event.target))
    ) {
        dropdown.classList.remove("show");
        if (btn) btn.classList.remove("active");
    }
});

// Close on Escape key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        const dropdown = document.getElementById("sellerNotifDropdown");
        const btn = document.getElementById("sellerNotifBtn");
        if (dropdown) dropdown.classList.remove("show");
        if (btn) btn.classList.remove("active");
    }
});
