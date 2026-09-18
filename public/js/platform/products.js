// Platform Admin Products Catalog Scripts

function showTakedownModal(productId, title, seller) {
    const config = window.PlatformProductsConfig || {};
    const baseUrl = config.productsBaseUrl || '/platform-admin/products';

    const titleElem = document.getElementById('modalProductTitle');
    const sellerElem = document.getElementById('modalProductSeller');
    const form = document.getElementById('takedownForm');
    const modal = document.getElementById('takedownModal');

    if (titleElem) titleElem.textContent = title;
    if (sellerElem) sellerElem.textContent = seller;
    if (form) form.action = `${baseUrl}/${productId}/takedown`;
    if (modal) modal.classList.add('show');
}

function closeTakedownModal() {
    const modal = document.getElementById('takedownModal');
    if (modal) modal.classList.remove('show');
}

let currentModalProduct = null;

function showPlatformModal(dataOrId, type, url, file) {
    const config = window.PlatformProductsConfig || {};
    const storageBaseUrl = config.storageBaseUrl || '/storage';

    let p = {};
    if (typeof dataOrId === 'object' && dataOrId !== null) {
        p = dataOrId;
    } else {
        // Fallback backward compatibility
        p = {
            id: dataOrId,
            title: 'Produk #' + dataOrId,
            platform_type: type || 'other',
            platform_url: url && url !== 'null' ? url : null,
            platform_file: file && file !== 'null' ? (storageBaseUrl + '/' + file) : null,
            platform_file_name: file && file !== 'null' ? file.split('/').pop() : null,
            is_active: true
        };
    }

    currentModalProduct = p;

    // 1. Cover Thumbnail
    const thumb = document.getElementById('modalDetailThumb');
    const placeholder = document.getElementById('modalDetailThumbPlaceholder');
    if (thumb && placeholder) {
        thumb.onerror = function() {
            this.style.display = 'none';
            placeholder.style.display = 'flex';
        };
        if (p.image) {
            thumb.src = p.image;
            thumb.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            thumb.style.display = 'none';
            placeholder.style.display = 'flex';
        }
    }

    // 2. Title & Status Badge
    const titleElem = document.getElementById('modalDetailTitle');
    if (titleElem) titleElem.textContent = p.title || '-';

    const statusBadge = document.getElementById('modalDetailStatusBadge');
    if (statusBadge) {
        if (p.is_active) {
            statusBadge.className = 'badge-live-dot';
            statusBadge.textContent = 'Aktif (Live)';
        } else {
            statusBadge.className = 'badge-takedown-pill';
            statusBadge.innerHTML = '<i class="fas fa-ban"></i> Ditakedown';
        }
    }

    // 3. Takedown Alert Banner
    const takedownAlert = document.getElementById('modalDetailTakedownAlert');
    const takedownDate = document.getElementById('modalDetailTakedownDate');
    const takedownReason = document.getElementById('modalDetailTakedownReason');
    if (takedownAlert) {
        if (!p.is_active) {
            takedownAlert.style.display = 'flex';
            if (takedownDate) takedownDate.textContent = 'Ditakedown pada: ' + (p.takedown_at || '-');
            if (takedownReason) takedownReason.textContent = 'Alasan: ' + (p.takedown_reason || 'Tidak ada catatan alasan.');
        } else {
            takedownAlert.style.display = 'none';
        }
    }

    // 4. Seller Info
    const sellerAvatar = document.getElementById('modalDetailSellerAvatar');
    if (sellerAvatar) {
        sellerAvatar.textContent = (p.seller_name || 'U').substring(0, 2).toUpperCase();
    }
    const sellerName = document.getElementById('modalDetailSellerName');
    if (sellerName) sellerName.textContent = p.seller_name || '-';
    const sellerEmail = document.getElementById('modalDetailSellerEmail');
    if (sellerEmail) sellerEmail.textContent = p.seller_email || '-';
    const createdAt = document.getElementById('modalDetailCreatedAt');
    if (createdAt) createdAt.innerHTML = `<i class="fas fa-calendar-alt"></i> ${p.created_at || '-'}`;

    // 5. Commercial Chips
    const priceDisplay = document.getElementById('modalDetailPriceDisplay');
    const strikePrice = document.getElementById('modalDetailStrikePrice');
    if (priceDisplay) {
        if (p.sale_price) {
            priceDisplay.textContent = 'Rp ' + p.sale_price;
            if (strikePrice) {
                strikePrice.textContent = 'Rp ' + p.price;
                strikePrice.style.display = 'inline';
            }
        } else {
            priceDisplay.textContent = 'Rp ' + (p.price || '0');
            if (strikePrice) strikePrice.style.display = 'none';
        }
    }

    const stockDisplay = document.getElementById('modalDetailStockDisplay');
    if (stockDisplay) {
        stockDisplay.textContent = p.has_quantity_limit ? (p.quantity + ' Stok') : 'Unlimited';
    }

    const soldDisplay = document.getElementById('modalDetailSoldDisplay');
    if (soldDisplay) {
        soldDisplay.textContent = (p.total_sold || 0) + ' Terjual';
    }

    const revenueDisplay = document.getElementById('modalDetailRevenueDisplay');
    if (revenueDisplay) {
        revenueDisplay.textContent = 'Rp ' + (p.total_revenue || '0') + ' Omzet';
    }

    // 6. Platform Type Badge with Icon
    const platformBadge = document.getElementById('modalDetailPlatformBadge');
    if (platformBadge) {
        const type = (p.platform_type || 'other').toLowerCase();
        let iconHtml = '<i class="fas fa-layer-group"></i>';
        let typeName = (p.platform_type || 'Other').toUpperCase();

        if (type === 'gdrive') {
            iconHtml = '<i class="fab fa-google-drive" style="color: #0f9d58;"></i>';
            typeName = 'Google Drive';
        } else if (type === 'dropbox') {
            iconHtml = '<i class="fab fa-dropbox" style="color: #0061ff;"></i>';
            typeName = 'Dropbox';
        } else if (type === 'upload') {
            iconHtml = '<i class="fas fa-cloud-upload-alt" style="color: #ed842c;"></i>';
            typeName = 'File Upload Langsung';
        } else if (type.includes('notion')) {
            iconHtml = '<i class="fas fa-book" style="color: #000000;"></i>';
            typeName = 'Notion Template';
        } else if (type.includes('telegram')) {
            iconHtml = '<i class="fab fa-telegram" style="color: #229ed9;"></i>';
            typeName = 'Telegram Channel';
        } else if (type.includes('zoom')) {
            iconHtml = '<i class="fas fa-video" style="color: #2d8cff;"></i>';
            typeName = 'Zoom Meeting';
        }

        platformBadge.innerHTML = `${iconHtml} <span>${typeName}</span>`;
    }

    // 7. URL Handling
    const urlRow = document.getElementById('modalDetailUrlRow');
    const urlInput = document.getElementById('modalDetailUrlInput');
    const urlLink = document.getElementById('modalDetailUrlLink');
    let hasUrl = false;
    if (p.platform_url && p.platform_url !== 'null' && p.platform_url.trim() !== '') {
        hasUrl = true;
        if (urlRow) urlRow.style.display = 'block';
        if (urlInput) urlInput.value = p.platform_url;
        if (urlLink) urlLink.href = p.platform_url;
    } else {
        if (urlRow) urlRow.style.display = 'none';
    }

    // 8. Deliverable URL / File
    const deliverableRow = document.getElementById('modalDetailDeliverableRow');
    const deliverableInput = document.getElementById('modalDetailDeliverableInput');
    const deliverableLink = document.getElementById('modalDetailDeliverableLink');
    let hasDeliverable = false;
    if (p.deliverable_url && p.deliverable_url !== p.platform_url && p.deliverable_url.trim() !== '') {
        hasDeliverable = true;
        let fullDeliverableUrl = p.deliverable_url.trim();
        if (!fullDeliverableUrl.startsWith('http://') && !fullDeliverableUrl.startsWith('https://')) {
            fullDeliverableUrl = storageBaseUrl + '/' + fullDeliverableUrl.replace(/^\/+/, '');
        }
        if (deliverableRow) deliverableRow.style.display = 'block';
        if (deliverableInput) deliverableInput.value = p.deliverable_url;
        if (deliverableLink) deliverableLink.href = fullDeliverableUrl;
    } else {
        if (deliverableRow) deliverableRow.style.display = 'none';
    }

    // 9. File Handling
    const fileRow = document.getElementById('modalDetailFileRow');
    const fileNameElem = document.getElementById('modalDetailFileName');
    const fileDownloadBtn = document.getElementById('modalDetailFileDownloadBtn');
    let hasFile = false;
    if (p.platform_file && p.platform_file !== 'null') {
        hasFile = true;
        if (fileRow) fileRow.style.display = 'block';
        if (fileNameElem) fileNameElem.textContent = p.platform_file_name || 'File Digital Terlampir';
        if (fileDownloadBtn) fileDownloadBtn.href = p.platform_file;
    } else {
        if (fileRow) fileRow.style.display = 'none';
    }

    // 10. No fulfillment note (only show if none of URL, Deliverable, or File exist)
    const noFulfillment = document.getElementById('modalDetailNoFulfillment');
    if (noFulfillment) {
        const hasAny = hasUrl || hasDeliverable || hasFile;
        noFulfillment.style.display = (!hasAny) ? 'flex' : 'none';
    }

    // 11. Description
    const descElem = document.getElementById('modalDetailDescription');
    if (descElem) {
        if (p.description && p.description.trim()) {
            descElem.innerHTML = p.description.replace(/\n/g, '<br>');
        } else {
            descElem.innerHTML = '<span style="color: #94a3b8; font-style: italic;">Tidak ada deskripsi produk.</span>';
        }
    }

    // 12. Gallery Media Files
    const galleryCard = document.getElementById('modalDetailGalleryCard');
    const galleryGrid = document.getElementById('modalDetailGalleryGrid');
    if (galleryCard && galleryGrid) {
        if (p.media_files && Array.isArray(p.media_files) && p.media_files.length > 0) {
            galleryCard.style.display = 'block';
            galleryGrid.innerHTML = p.media_files.map(imgUrl => `
                <a href="${imgUrl}" target="_blank" class="gallery-thumb-item">
                    <img src="${imgUrl}" alt="Media File">
                </a>
            `).join('');
        } else {
            galleryCard.style.display = 'none';
        }
    }

    // 13. Seller Store Link
    const storeLink = document.getElementById('modalDetailSellerStoreLink');
    if (storeLink) {
        if (p.seller_url) {
            storeLink.href = p.seller_url;
            storeLink.style.display = 'inline-flex';
        } else {
            storeLink.style.display = 'none';
        }
    }

    // 14. Quick Action Moderation Buttons
    const quickTakedownBtn = document.getElementById('modalDetailQuickTakedownBtn');
    const quickRestoreBtn = document.getElementById('modalDetailQuickRestoreBtn');
    if (quickTakedownBtn && quickRestoreBtn) {
        if (p.is_active) {
            quickTakedownBtn.style.display = 'inline-flex';
            quickRestoreBtn.style.display = 'none';
        } else {
            quickTakedownBtn.style.display = 'none';
            quickRestoreBtn.style.display = 'inline-flex';
        }
    }

    const modal = document.getElementById('platformModal');
    if (modal) modal.classList.add('show');
}

function closePlatformModal() {
    const modal = document.getElementById('platformModal');
    if (modal) modal.classList.remove('show');
}

function copyModalUrl() {
    const input = document.getElementById('modalDetailUrlInput');
    const textSpan = document.getElementById('copyUrlText');
    if (input && input.value) {
        navigator.clipboard.writeText(input.value).then(() => {
            if (textSpan) {
                textSpan.textContent = 'Tersalin!';
                setTimeout(() => { textSpan.textContent = 'Salin'; }, 2000);
            }
        });
    }
}

function triggerTakedownFromDetail() {
    if (!currentModalProduct) return;
    const p = currentModalProduct;
    closePlatformModal();
    showTakedownModal(p.id, p.title, p.seller_name);
}

function triggerRestoreFromDetail() {
    if (!currentModalProduct) return;
    const p = currentModalProduct;
    closePlatformModal();
    showRestoreModal(p.id, p.title, p.seller_name);
}

function showRestoreModal(productId, title, seller) {
    const config = window.PlatformProductsConfig || {};
    const baseUrl = config.productsBaseUrl || '/platform-admin/products';

    const titleElem = document.getElementById('modalRestoreProductTitle');
    const sellerElem = document.getElementById('modalRestoreProductSeller');
    const form = document.getElementById('restoreForm');
    const modal = document.getElementById('restoreModal');
    const reasonInput = document.getElementById('modalRestoreReason');

    if (titleElem) titleElem.textContent = title;
    if (sellerElem) sellerElem.textContent = seller;
    if (form) form.action = `${baseUrl}/${productId}/restore`;
    if (reasonInput) reasonInput.value = '';
    if (modal) modal.classList.add('show');
}

function closeRestoreModal() {
    const modal = document.getElementById('restoreModal');
    if (modal) modal.classList.remove('show');
}

window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        closeTakedownModal();
        closeRestoreModal();
        closePlatformModal();
    }
});

function confirmRestoreProduct(form, productTitle) {
    const config = window.PlatformProductsConfig || {};
    const restoreText = config.restoreProductText || 'Pulihkan Produk';

    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Pulihkan Produk Digital?',
            text: `Produk "${productTitle}" akan diaktifkan kembali dan dapat diakses/dibeli pembeli di etalase microsite seller.`,
            icon: 'question',
            confirmText: `<i class="fas fa-undo"></i> ${restoreText}`,
            onConfirm: () => {
                form.submit();
            }
        });
    } else {
        if (confirm(`Pulihkan produk "${productTitle}"?`)) {
            form.submit();
        }
    }
}

function clearProductSearch() {
    const input = document.getElementById('productSearchInput');
    if (input) {
        input.value = '';
        const form = input.closest('form');
        if (form) form.submit();
    }
}
