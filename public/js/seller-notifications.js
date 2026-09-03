// Seller Admin Notifications Scripts with Fast Non-Blocking Polling

let sellerNotifsData = {
    unread_count: 0,
    notifications: [],
};

let currentSellerNotifFilter = "all";
let sellerNotifTimer = null;
let isFetchingSellerNotifs = false;

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
        fetchSellerNotifs();
    }
}

function updateSellerUI(data) {
    if (!data) return;
    sellerNotifsData = data;
    const badge = document.getElementById("sellerNotifBadge");
    const totalPill = document.getElementById("sellerNotifTotal");

    // Update badge counter
    const count = data.unread_count || 0;
    if (badge) {
        if (count > 0) {
            badge.innerText = count > 99 ? "99+" : count;
            badge.style.display = "flex";
        } else {
            badge.style.display = "none";
        }
    }

    if (totalPill) {
        totalPill.innerText = count + " Baru";
    }

    renderSellerNotifs(currentSellerNotifFilter);
}

function getSellerEndpoint() {
    // Selalu gunakan root-relative path agar aman dari Mixed Content / Cloudflare Tunnel / CORS
    return "/admin/notifications";
}

function fetchSellerNotifs() {
    if (isFetchingSellerNotifs) return;
    isFetchingSellerNotifs = true;

    const endpoint = getSellerEndpoint();
    const listContainer = document.getElementById("sellerNotifList");

    fetch(endpoint, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
            "Cache-Control": "no-cache",
        },
    })
        .then((response) => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then((data) => {
            updateSellerUI(data);
        })
        .catch((err) => {
            console.warn("Failed to load seller notifications:", err);
            if (
                listContainer &&
                (!sellerNotifsData.notifications ||
                    sellerNotifsData.notifications.length === 0)
            ) {
                listContainer.innerHTML = `
                <div class="seller-notif-empty">
                    <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                    <p>Gagal memuat notifikasi.</p>
                </div>
            `;
            }
        })
        .finally(() => {
            isFetchingSellerNotifs = false;
        });
}

function startSellerRealtimePolling() {
    if (sellerNotifTimer) clearInterval(sellerNotifTimer);

    // Polling cepat setiap 2.5 detik saat tab aktif
    sellerNotifTimer = setInterval(() => {
        if (document.visibilityState === "visible") {
            fetchSellerNotifs();
        }
    }, 2500);
}

// Pause saat tab disembunyikan, langsung fetch saat tab kembali aktif
document.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "visible") {
        fetchSellerNotifs();
    }
});

function filterSellerNotif(type, buttonElem) {
    currentSellerNotifFilter = type;

    // Update active tab class
    const tabs = document.querySelectorAll(
        ".seller-notif-filter-tabs .seller-notif-tab",
    );
    tabs.forEach((tab) => tab.classList.remove("active"));
    if (buttonElem) {
        buttonElem.classList.add("active");
    }

    renderSellerNotifs(type);
}

function renderSellerNotifs(filterType) {
    const listContainer = document.getElementById("sellerNotifList");
    if (!listContainer) return;

    let items = sellerNotifsData.notifications || [];
    if (filterType && filterType !== "all") {
        items = items.filter((item) => item.type === filterType);
    }

    if (items.length === 0) {
        listContainer.innerHTML = `
            <div class="seller-notif-empty">
                <i class="far fa-bell-slash"></i>
                <p>Tidak ada notifikasi ${filterType !== "all" ? "untuk kategori ini" : "baru"}.</p>
            </div>
        `;
        return;
    }

    let html = "";
    items.forEach((item) => {
        html += `
            <a href="${item.url}" class="seller-notif-item ${item.is_read ? "is-read" : "is-unread"}" onclick="markSellerNotifRead(event, '${item.id}')">
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

function markSellerNotifRead(event, notificationKey) {
    event.preventDefault();
    fetch(window.SellerNotifReadEndpoint, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: new URLSearchParams({ notification_key: notificationKey }),
    }).finally(() => {
        window.location.href = event.currentTarget.href;
    });
}

function markAllSellerNotifsRead(event) {
    event.stopPropagation();
    fetch(window.SellerNotifReadAllEndpoint, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    }).then(() => fetchSellerNotifs());
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

// Jalankan langsung dan aktifkan timer polling
function initSellerNotifs() {
    fetchSellerNotifs();
    startSellerRealtimePolling();
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSellerNotifs);
} else {
    initSellerNotifs();
}
