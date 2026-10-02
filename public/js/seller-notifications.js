/**
 * seller-notifications.js
 * Linkan.ID - Seller Header Notification Manager
 * Handles notification toggle, instant unread badge dismissal, and background synchronization.
 */

function getNotificationEndpoints() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const isEn = window.location.pathname.startsWith('/en');
    const defaultLocale = isEn ? '/en' : '/id';

    const readAllUrl = window.SellerNotifReadAllEndpoint 
        || (window.sellerNotifConfig && window.sellerNotifConfig.readAllUrl)
        || `${defaultLocale}/admin/notifications/read-all`;

    const readUrl = window.SellerNotifReadEndpoint 
        || (window.sellerNotifConfig && window.sellerNotifConfig.readUrl)
        || `${defaultLocale}/admin/notifications/read`;

    return { csrfToken, readAllUrl, readUrl };
}

/**
 * Dismiss badge in UI instantly without screen flicker
 */
function dismissNotificationBadgeUI() {
    const badge = document.getElementById("sellerNotifBadge");
    if (badge) {
        badge.style.display = 'none';
        badge.textContent = '0';
    }
    const headerBadge = document.querySelector(".seller-notif-badge-header");
    if (headerBadge) {
        headerBadge.textContent = '00 Notifications';
    }
}

/**
 * Toggle notification dropdown on bell icon click
 * Immediately hides the unread notification counter badge and marks notifications as read silently.
 */
function toggleSellerNotif(event) {
    if (event) {
        event.preventDefault();
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
        
        // Instantly hide badge upon clicking/opening the notifications
        dismissNotificationBadgeUI();
        
        // Silently synchronize read status with the server in the background (silent: true avoids global overlay)
        if (typeof markAllSellerNotifsRead === 'function') {
            markAllSellerNotifsRead(null, true);
        }
    }
}

/**
 * Mark a single notification as read
 */
function markSellerNotifRead(event, notificationKey, element) {
    if (event && event.currentTarget && event.currentTarget.getAttribute('href') === '#') {
        event.preventDefault();
    }
    
    if (element) {
        element.style.opacity = '0.5';
        element.style.pointerEvents = 'none';
    }
    
    const targetHref = event && event.currentTarget ? event.currentTarget.getAttribute('href') : null;
    const { csrfToken, readUrl } = getNotificationEndpoints();

    // Optimistically decrement or dismiss badge
    const badge = document.getElementById("sellerNotifBadge");
    if (badge && badge.style.display !== 'none') {
        const currentCount = parseInt(badge.textContent.trim(), 10);
        if (!isNaN(currentCount) && currentCount > 1) {
            const nextCount = currentCount - 1;
            badge.textContent = nextCount > 99 ? '99+' : String(nextCount);
            const headerBadge = document.querySelector(".seller-notif-badge-header");
            if (headerBadge) {
                headerBadge.textContent = `${String(nextCount).padStart(2, '0')} Notifications`;
            }
        } else {
            dismissNotificationBadgeUI();
        }
    }

    if (!csrfToken) {
        if (targetHref && targetHref !== '#' && !targetHref.endsWith('#')) {
            window.location.href = targetHref;
        }
        return;
    }

    const payload = new URLSearchParams({ notification_key: notificationKey });

    fetch(readUrl, {
        method: "POST",
        silent: true,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: payload,
    })
    .catch(() => {
        // Fallback without locale prefix
        return fetch('/admin/notifications/read', {
            method: "POST",
            silent: true,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: payload,
        });
    })
    .finally(() => {
        if (targetHref && targetHref !== '#' && !targetHref.endsWith('#')) {
            window.location.href = targetHref;
        }
    });
}

/**
 * Mark all notifications as read (either on bell click or explicit button click)
 */
function markAllSellerNotifsRead(event, silent = false, element = null) {
    if (event) event.stopPropagation();

    // Immediately hide badge in the UI
    dismissNotificationBadgeUI();

    if (element) {
        element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        element.style.pointerEvents = 'none';
    }

    const { csrfToken, readAllUrl } = getNotificationEndpoints();
    if (!csrfToken) return;

    fetch(readAllUrl, {
        method: "POST",
        silent: true,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
    })
    .catch(() => {
        // Fallback without locale prefix
        return fetch('/admin/notifications/read-all', {
            method: "POST",
            silent: true,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
        });
    })
    .finally(() => {
        dismissNotificationBadgeUI();

        if (element) {
            element.innerHTML = '<i class="fas fa-check"></i> Selesai';
            setTimeout(() => {
                element.textContent = 'Read All Messages';
                element.style.pointerEvents = 'auto';
            }, 2000);
        }
    });
}

// Global click-outside listener to close dropdown
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
