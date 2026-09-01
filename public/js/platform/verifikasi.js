// Platform Admin Product Verification Scripts

function showPlatformModal(productId, platformType, platformUrl, platformFile) {
    const config = window.PlatformVerifikasiConfig || {};
    const storageBaseUrl = config.storageBaseUrl || "/storage";
    const viewFileText = config.viewFileText || "Lihat File";

    const pTypeElem = document.getElementById("platformType");
    if (pTypeElem) {
        pTypeElem.textContent =
            platformType.charAt(0).toUpperCase() + platformType.slice(1);
    }

    const urlGroup = document.getElementById("platformUrlGroup");
    const fileGroup = document.getElementById("platformFileGroup");
    const fileElem = document.getElementById("platformFile");
    const urlElem = document.getElementById("platformUrl");

    if (platformType === "upload") {
        if (urlGroup) urlGroup.style.display = "none";
        if (fileGroup) fileGroup.style.display = "block";
        if (fileElem) {
            fileElem.innerHTML = `<a href="${storageBaseUrl}/${platformFile}" target="_blank" style="display:inline-flex; align-items:center; gap:6px; color:#5A5BF1; font-weight:700; text-decoration:none; background:#EEF0FE; padding:8px 14px; border-radius:8px;"><i class="fas fa-download"></i> ${viewFileText}</a>`;
        }
    } else {
        if (urlGroup) urlGroup.style.display = "block";
        if (fileGroup) fileGroup.style.display = "none";
        if (urlElem) {
            urlElem.innerHTML = `<a href="${platformUrl}" target="_blank" style="color:#5A5BF1; font-weight:700; text-decoration:underline;">${platformUrl}</a>`;
        }
    }

    const modal = document.getElementById("platformModal");
    if (modal) modal.classList.add("show");
    document.body.style.overflow = "hidden";
}

function showProductDetail(button) {
    const data = button.dataset;
    const imageWrap = document.getElementById("productImageWrap");
    const titleElem = document.getElementById("productTitle");
    const descriptionElem = document.getElementById("productDescription");
    const priceElem = document.getElementById("productPrice");
    const dateElem = document.getElementById("productDate");
    const quantityElem = document.getElementById("productQuantity");

    if (titleElem) titleElem.textContent = data.title || "-";
    if (descriptionElem) descriptionElem.textContent = data.description || "-";
    if (priceElem) priceElem.textContent = data.price || "-";
    if (dateElem) dateElem.textContent = data.date || "-";
    if (quantityElem) quantityElem.textContent = data.quantity || "-";
    if (imageWrap) {
        imageWrap.replaceChildren();
        if (data.image) {
            const image = document.createElement("img");
            image.src = data.image;
            image.alt = data.title || "";
            image.className = "product-modal-image";
            imageWrap.appendChild(image);
        } else {
            imageWrap.innerHTML = '<i class="fas fa-box"></i>';
        }
    }

    showPlatformModal(
        null,
        data.platform || "",
        data.platformUrl || "",
        data.platformFile || "",
    );
}

function closePlatformModal() {
    const modal = document.getElementById("platformModal");
    if (modal) modal.classList.remove("show");
    document.body.style.overflow = "auto";
}

function showRejectModal(productId) {
    const config = window.PlatformVerifikasiConfig || {};
    const rejectBaseUrl = config.rejectBaseUrl || "/platform-admin/verifikasi";
    const form = document.getElementById("rejectForm");
    const modal = document.getElementById("rejectModal");

    if (form) form.action = `${rejectBaseUrl}/${productId}`;
    if (modal) modal.classList.add("show");
    document.body.style.overflow = "hidden";
}

function closeRejectModal() {
    const modal = document.getElementById("rejectModal");
    if (modal) modal.classList.remove("show");
    document.body.style.overflow = "auto";
}

function showDescriptionModal(element) {
    const title = element.dataset.title || "Deskripsi Produk Digital";
    const description = element.dataset.fullDescription || "";
    const titleElem = document.getElementById("descModalTitle");
    const descElem = document.getElementById("fullDescription");
    const modal = document.getElementById("descriptionModal");

    if (titleElem) titleElem.textContent = title;
    if (descElem) descElem.textContent = description;
    if (modal) modal.classList.add("show");
    document.body.style.overflow = "hidden";
}

function closeDescriptionModal() {
    const modal = document.getElementById("descriptionModal");
    if (modal) modal.classList.remove("show");
    document.body.style.overflow = "auto";
}

function getSelectedProductIds() {
    return Array.from(
        document.querySelectorAll(".product-checkbox:checked"),
    ).map((checkbox) => checkbox.value);
}

function updateBulkSelection() {
    const selectedIds = getSelectedProductIds();
    const count = selectedIds.length;
    const countElem = document.getElementById("selectedCount");
    const statusButtons = document.querySelectorAll(".bulk-btn");
    const selectAll = document.getElementById("selectAllProducts");
    const visibleCheckboxes = Array.from(
        document.querySelectorAll(".product-checkbox"),
    ).filter((checkbox) => {
        return checkbox.closest(".product-row")?.style.display !== "none";
    });

    if (countElem) countElem.textContent = count;
    statusButtons.forEach((button) => {
        button.disabled = count === 0;
    });
    if (selectAll) {
        selectAll.checked =
            visibleCheckboxes.length > 0 &&
            visibleCheckboxes.every((checkbox) => checkbox.checked);
        selectAll.indeterminate =
            visibleCheckboxes.some((checkbox) => checkbox.checked) &&
            !selectAll.checked;
    }
}

function prepareBulkForm(status, rejectionReason = "") {
    const form = document.getElementById("bulkActionForm");
    const statusInput = document.getElementById("bulkStatus");
    const idsContainer = document.getElementById("bulkProductIds");
    if (!form || !statusInput || !idsContainer) return null;

    statusInput.value = status;
    idsContainer.replaceChildren();
    getSelectedProductIds().forEach((id) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "product_ids[]";
        input.value = id;
        idsContainer.appendChild(input);
    });

    if (status === "rejected") {
        const reason = document.createElement("input");
        reason.type = "hidden";
        reason.name = "rejection_reason";
        reason.value = rejectionReason;
        idsContainer.appendChild(reason);
    }
    return form;
}

function submitBulkAction(status) {
    const selectedCount = getSelectedProductIds().length;
    if (!selectedCount) return;

    const form = prepareBulkForm(status);
    if (!form) return;

    showConfirmModal({
        title:
            status === "approved"
                ? "Setujui produk terpilih?"
                : "Tolak produk terpilih?",
        text: `${selectedCount} produk akan diproses sekaligus.`,
        icon: status === "approved" ? "question" : "warning",
        confirmText: status === "approved" ? "Setujui" : "Tolak",
        confirmDanger: status === "rejected",
        onConfirm: () => {
            const actionButton = document.querySelector(
                status === "approved" ? ".bulk-approve" : ".bulk-reject",
            );
            if (typeof setPlatformActionLoading === "function") {
                setPlatformActionLoading(actionButton);
            }
            form.submit();
        },
    });
}

function openBulkRejectModal() {
    if (!getSelectedProductIds().length) return;
    const modal = document.getElementById("bulkRejectModal");
    const reason = document.getElementById("bulkRejectionReason");
    if (reason) reason.value = "";
    if (modal) modal.classList.add("show");
    document.body.style.overflow = "hidden";
}

function closeBulkRejectModal() {
    const modal = document.getElementById("bulkRejectModal");
    if (modal) modal.classList.remove("show");
    document.body.style.overflow = "auto";
}

function submitBulkReject() {
    const reason = document.getElementById("bulkRejectionReason")?.value.trim();
    if (!reason) {
        document.getElementById("bulkRejectionReason")?.focus();
        return;
    }
    const form = prepareBulkForm("rejected", reason);
    closeBulkRejectModal();
    if (form) {
        const actionButton = document.querySelector(".bulk-reject");
        if (typeof setPlatformActionLoading === "function") {
            setPlatformActionLoading(actionButton);
        }
        form.submit();
    }
}

// Close modal on backdrop click
window.addEventListener("click", function (event) {
    if (event.target.classList.contains("modal")) {
        closeRejectModal();
        closePlatformModal();
        closeDescriptionModal();
        closeBulkRejectModal();
    }
});

// Filter and Search
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".tab-btn");
    const searchInput = document.getElementById("searchInput");
    const platformFilter = document.getElementById("platformFilter");
    const filterStartDate = document.getElementById("filterStartDate") || document.getElementById("startDate") || document.getElementById("filterDate");
    const filterEndDate = document.getElementById("filterEndDate") || document.getElementById("endDate");
    const filterDateBox = document.getElementById("verificationDateRange") || document.querySelector(".date-picker-box");
    const productRows = document.querySelectorAll(".product-row");
    const noDataMessage = document.getElementById("noDataMessage");

    let currentActiveTab = "pending";

    function filterProducts() {
        const searchTerm = searchInput
            ? searchInput.value.toLowerCase().trim()
            : "";
        const platformValue = platformFilter ? platformFilter.value : "";
        const startVal = filterStartDate ? filterStartDate.value : "";
        const endVal = filterEndDate ? filterEndDate.value : "";
        const activeTab = currentActiveTab;

        let visibleCount = 0;

        productRows.forEach((row) => {
            const status = row.dataset.status;
            const platform = row.dataset.platform;
            const date = row.dataset.date;
            const title = row.dataset.title || "";
            const username = row.querySelector("td:nth-child(3)")
                ? row.querySelector("td:nth-child(3)").textContent.toLowerCase()
                : "";
            const description = (row.dataset.description || "").toLowerCase();

            const matchesSearch =
                !searchTerm ||
                title.includes(searchTerm) ||
                username.includes(searchTerm) ||
                description.includes(searchTerm);

            const matchesPlatform =
                !platformValue || platform === platformValue;

            let matchesDate = true;
            if (startVal && endVal) {
                matchesDate = date >= startVal && date <= endVal;
            } else if (startVal) {
                matchesDate = date >= startVal;
            } else if (endVal) {
                matchesDate = date <= endVal;
            }

            let matchesStatus = false;
            if (activeTab === "pending") {
                matchesStatus = status === "pending";
            } else if (activeTab === "approved") {
                matchesStatus = status === "approved";
            } else if (activeTab === "rejected") {
                matchesStatus = status === "rejected";
            } else if (activeTab === "archive") {
                matchesStatus = status !== "pending";
            } else {
                matchesStatus = true;
            }

            if (
                matchesSearch &&
                matchesPlatform &&
                matchesDate &&
                matchesStatus
            ) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });

        if (noDataMessage) {
            noDataMessage.style.display = visibleCount === 0 ? "block" : "none";
        }
        updateBulkSelection();
    }

    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            tabs.forEach((t) => {
                t.classList.remove("active");
                t.classList.remove("is-expanded");
            });
            this.classList.add("active");
            this.classList.add("is-expanded");
            currentActiveTab = this.dataset.tab || "pending";
            filterProducts();
        });
    });

    if (searchInput) searchInput.addEventListener("input", filterProducts);
    if (platformFilter)
        platformFilter.addEventListener("change", filterProducts);
    if (filterStartDate) filterStartDate.addEventListener("change", filterProducts);
    if (filterEndDate) filterEndDate.addEventListener("change", filterProducts);
    if (filterDateBox) filterDateBox.addEventListener("dateRangeChange", filterProducts);

    document.querySelectorAll(".product-checkbox").forEach((checkbox) => {
        checkbox.addEventListener("change", updateBulkSelection);
    });

    const selectAll = document.getElementById("selectAllProducts");
    if (selectAll) {
        selectAll.addEventListener("change", function () {
            document
                .querySelectorAll(".product-checkbox")
                .forEach((checkbox) => {
                    const row = checkbox.closest(".product-row");
                    if (row && row.style.display !== "none")
                        checkbox.checked = this.checked;
                });
            updateBulkSelection();
        });
    }

    filterProducts();
});

function confirmApproveProduct(form) {
    const config = window.PlatformVerifikasiConfig || {};
    const approveText = config.approveText || "Setujui";

    if (typeof showConfirmModal === "function") {
        showConfirmModal({
            title: "Setujui Verifikasi Produk?",
            text: "Produk digital ini akan langsung diverifikasi, disetujui, dan berstatus live di etalase microsite seller.",
            icon: "question",
            confirmText: `<i class="fas fa-check"></i> ${approveText}`,
            onConfirm: () => {
                const approveButton = form.querySelector(
                    'button[type="button"]',
                );
                if (typeof setPlatformActionLoading === "function") {
                    setPlatformActionLoading(approveButton);
                }
                form.submit();
            },
        });
    } else {
        if (confirm("Setujui verifikasi produk ini?")) {
            form.submit();
        }
    }
}
