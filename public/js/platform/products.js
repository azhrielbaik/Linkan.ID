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

function showPlatformModal(id, type, url, file) {
    const config = window.PlatformProductsConfig || {};
    const storageBaseUrl = config.storageBaseUrl || '/storage';
    const viewFileText = config.viewFileText || 'Lihat File';

    const typeElem = document.getElementById('platformType');
    if (typeElem) typeElem.textContent = type.toUpperCase();
    
    const urlGroup = document.getElementById('platformUrlGroup');
    const fileGroup = document.getElementById('platformFileGroup');
    const urlElem = document.getElementById('platformUrl');
    const fileElem = document.getElementById('platformFile');
    
    if (url && url !== 'null') {
        if (urlGroup) urlGroup.style.display = 'block';
        if (urlElem) urlElem.innerHTML = `<a href="${url}" target="_blank" style="color: #5A5BF1; text-decoration: underline; font-weight: 600;">${url}</a>`;
    } else {
        if (urlGroup) urlGroup.style.display = 'none';
    }
    
    if (file && file !== 'null') {
        if (fileGroup) fileGroup.style.display = 'block';
        if (fileElem) fileElem.innerHTML = `<a href="${storageBaseUrl}/${file}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #EEF0FE; color: #5A5BF1; padding: 8px 14px; border-radius: 8px; font-weight: 700; text-decoration: none;"><i class="fas fa-download"></i> ${viewFileText}</a>`;
    } else {
        if (fileGroup) fileGroup.style.display = 'none';
    }
    
    const modal = document.getElementById('platformModal');
    if (modal) modal.classList.add('show');
}

function closePlatformModal() {
    const modal = document.getElementById('platformModal');
    if (modal) modal.classList.remove('show');
}

window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        closeTakedownModal();
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
