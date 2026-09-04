// Platform Admin Notifications Scripts using Server-Sent Events (SSE)

let platformNotifsData = {
    unread_count: 0,
    counts: { products: 0, payouts: 0, appeals: 0 },
    notifications: [],
};

let currentNotifFilter = "all";
let platformNotifTimer = null;
let isFetchingPlatformNotifs = false;

function togglePlatformNotif(event) {
    if (event) {
        event.stopPropagation();
    }
    const dropdown = document.getElementById("platformNotifDropdown");
    const btn = document.getElementById("platformNotifBtn");
    if (!dropdown) return;

    const isOpen = dropdown.classList.contains("show");
    if (isOpen) {
        dropdown.classList.remove("show");
        if (btn) btn.classList.remove("active");
    } else {
        dropdown.classList.add("show");
        if (btn) btn.classList.add("active");
        fetchPlatformNotifs();
    }
}

function updatePlatformUI(data) {
    if (!data) return;
    platformNotifsData = data;
    const badge = document.getElementById("platformNotifBadge");
    const totalPill = document.getElementById("platformNotifTotal");

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

    renderPlatformNotifs(currentPlatformNotifFilter);
}

function getPlatformEndpoint() {
    // Selalu gunakan root-relative path agar aman dari Mixed Content / Cloudflare Tunnel / CORS
    return "/platform-admin/notifications";
}

function fetchPlatformNotifs() {
    if (isFetchingPlatformNotifs) return;
    isFetchingPlatformNotifs = true;

    const endpoint = getPlatformEndpoint();
    const listContainer = document.getElementById("platformNotifList");

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
            updatePlatformUI(data);
        })
        .catch((err) => {
            console.warn("Failed to load platform notifications:", err);
            if (
                listContainer &&
                (!platformNotifsData.notifications ||
                    platformNotifsData.notifications.length === 0)
            ) {
                listContainer.innerHTML = `
                <div class="notif-empty">
                    <i class="fas fa-wifi" style="color: #f59e0b;"></i>
                    <p>Menghubungkan ulang ke server...</p>
                </div>
            `;
            }
        })
        .finally(() => {
            isFetchingPlatformNotifs = false;
        });
}

function startPlatformRealtimePolling() {
    if (platformNotifTimer) clearInterval(platformNotifTimer);

    // Polling cepat setiap 2.5 detik saat tab aktif
    platformNotifTimer = setInterval(() => {
        if (document.visibilityState === "visible") {
            fetchPlatformNotifs();
        }
    }, 2500);
}

// Pause saat tab disembunyikan, langsung fetch saat tab kembali aktif
document.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "visible") {
        fetchPlatformNotifs();
    }
});

function filterPlatformNotif(type, buttonElem) {
    currentNotifFilter = type;

    // Update active tab class
    const tabs = document.querySelectorAll(".notif-filter-tabs .notif-tab");
    tabs.forEach((tab) => tab.classList.remove("active"));
    if (buttonElem) {
        buttonElem.classList.add("active");
    }

    renderPlatformNotifs(type);
}

function renderPlatformNotifs(filterType) {
    const listContainer = document.getElementById("platformNotifList");
    if (!listContainer) return;

    let items = platformNotifsData.notifications || [];
    if (filterType && filterType !== "all") {
        items = items.filter((item) => item.type === filterType);
    }

    if (items.length === 0) {
        listContainer.innerHTML = `
            <div class="notif-empty">
                <i class="far fa-bell-slash"></i>
                <p>Tidak ada notifikasi ${filterType !== "all" ? "untuk kategori ini" : "baru"}.</p>
            </div>
        `;
        return;
    }

    let html = "";
    items.forEach((item) => {
        html += `
            <a href="${item.url}" class="notif-item ${item.is_read ? "is-read" : "is-unread"}" onclick="markPlatformNotifRead(event, '${item.id}')">
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

function markPlatformNotifRead(event, notificationKey) {
    event.preventDefault();
    fetch(window.PlatformNotifReadEndpoint, {
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

function markAllPlatformNotifsRead(event) {
    event.stopPropagation();
    fetch(window.PlatformNotifReadAllEndpoint, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    }).then(() => fetchPlatformNotifs());
}

// Global click-outside listener
document.addEventListener("click", function (event) {
    const dropdown = document.getElementById("platformNotifDropdown");
    const btn = document.getElementById("platformNotifBtn");
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
        const dropdown = document.getElementById("platformNotifDropdown");
        const btn = document.getElementById("platformNotifBtn");
        if (dropdown) dropdown.classList.remove("show");
        if (btn) btn.classList.remove("active");
    }
});

// Start the SSE connection when page loads
function initPlatformNotifs() {
    startPlatformRealtimeSSE();
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPlatformNotifs);
} else {
    initPlatformNotifs();
}

window.addEventListener("beforeunload", function () {
    if (window.platformEventSource) {
        window.platformEventSource.close();
    }
});
