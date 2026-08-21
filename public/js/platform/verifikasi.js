// Platform Admin Product Verification Scripts

function showPlatformModal(productId, platformType, platformUrl, platformFile) {
    const config = window.PlatformVerifikasiConfig || {};
    const storageBaseUrl = config.storageBaseUrl || '/storage';
    const viewFileText = config.viewFileText || 'Lihat File';

    const pTypeElem = document.getElementById('platformType');
    if (pTypeElem) {
        pTypeElem.textContent = platformType.charAt(0).toUpperCase() + platformType.slice(1);
    }
    
    const urlGroup = document.getElementById('platformUrlGroup');
    const fileGroup = document.getElementById('platformFileGroup');
    const fileElem = document.getElementById('platformFile');
    const urlElem = document.getElementById('platformUrl');
    
    if (platformType === 'upload') {
        if (urlGroup) urlGroup.style.display = 'none';
        if (fileGroup) fileGroup.style.display = 'block';
        if (fileElem) {
            fileElem.innerHTML = `<a href="${storageBaseUrl}/${platformFile}" target="_blank" style="display:inline-flex; align-items:center; gap:6px; color:#5A5BF1; font-weight:700; text-decoration:none; background:#EEF0FE; padding:8px 14px; border-radius:8px;"><i class="fas fa-download"></i> ${viewFileText}</a>`;
        }
    } else {
        if (urlGroup) urlGroup.style.display = 'block';
        if (fileGroup) fileGroup.style.display = 'none';
        if (urlElem) {
            urlElem.innerHTML = `<a href="${platformUrl}" target="_blank" style="color:#5A5BF1; font-weight:700; text-decoration:underline;">${platformUrl}</a>`;
        }
    }
    
    const modal = document.getElementById('platformModal');
    if (modal) modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closePlatformModal() {
    const modal = document.getElementById('platformModal');
    if (modal) modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function showRejectModal(productId) {
    const config = window.PlatformVerifikasiConfig || {};
    const rejectBaseUrl = config.rejectBaseUrl || '/platform-admin/verifikasi';
    const form = document.getElementById('rejectForm');
    const modal = document.getElementById('rejectModal');

    if (form) form.action = `${rejectBaseUrl}/${productId}`;
    if (modal) modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function showDescriptionModal(element) {
    const title = element.dataset.title || 'Deskripsi Produk Digital';
    const description = element.dataset.fullDescription || '';
    const titleElem = document.getElementById('descModalTitle');
    const descElem = document.getElementById('fullDescription');
    const modal = document.getElementById('descriptionModal');

    if (titleElem) titleElem.textContent = title;
    if (descElem) descElem.textContent = description;
    if (modal) modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDescriptionModal() {
    const modal = document.getElementById('descriptionModal');
    if (modal) modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Close modal on backdrop click
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        closeRejectModal();
        closePlatformModal();
        closeDescriptionModal();
    }
});

// Filter and Search
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    const searchInput = document.getElementById('searchInput');
    const platformFilter = document.getElementById('platformFilter');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const productRows = document.querySelectorAll('.product-row');
    const noDataMessage = document.getElementById('noDataMessage');

    function filterProducts() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const platformValue = platformFilter ? platformFilter.value : '';
        const startDateValue = startDate ? startDate.value : '';
        const endDateValue = endDate ? endDate.value : '';
        const activeTabElement = document.querySelector('.tab-btn.active');
        const activeTab = activeTabElement ? activeTabElement.dataset.tab : 'pending';
        
        let visibleCount = 0;

        productRows.forEach(row => {
            const status = row.dataset.status;
            const platform = row.dataset.platform;
            const date = row.dataset.date;
            const title = row.dataset.title || '';
            const username = row.querySelector('td:nth-child(2)') ? row.querySelector('td:nth-child(2)').textContent.toLowerCase() : '';
            const description = row.querySelector('td:nth-child(4)') ? row.querySelector('td:nth-child(4)').textContent.toLowerCase() : '';

            const matchesSearch = !searchTerm || 
                                  title.includes(searchTerm) || 
                                  username.includes(searchTerm) || 
                                  description.includes(searchTerm);
            
            const matchesPlatform = !platformValue || platform === platformValue;
            const matchesDate = (!startDateValue || date >= startDateValue) && 
                                (!endDateValue || date <= endDateValue);

            let matchesStatus = false;
            if (activeTab === 'pending') {
                matchesStatus = status === 'pending';
            } else if (activeTab === 'approved') {
                matchesStatus = status === 'approved';
            } else if (activeTab === 'rejected') {
                matchesStatus = status === 'rejected';
            } else if (activeTab === 'archive') {
                matchesStatus = status !== 'pending'; 
            } else {
                matchesStatus = true; 
            }

            if (matchesSearch && matchesPlatform && matchesDate && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (noDataMessage) {
            noDataMessage.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            filterProducts();
        });
    });

    if (searchInput) searchInput.addEventListener('input', filterProducts);
    if (platformFilter) platformFilter.addEventListener('change', filterProducts);
    if (startDate) startDate.addEventListener('change', filterProducts);
    if (endDate) endDate.addEventListener('change', filterProducts);

    filterProducts();
});

function confirmApproveProduct(form) {
    const config = window.PlatformVerifikasiConfig || {};
    const approveText = config.approveText || 'Setujui';

    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Setujui Verifikasi Produk?',
            text: 'Produk digital ini akan langsung diverifikasi, disetujui, dan berstatus live di etalase microsite seller.',
            icon: 'question',
            confirmText: `<i class="fas fa-check"></i> ${approveText}`,
            onConfirm: () => {
                form.submit();
            }
        });
    } else {
        if (confirm('Setujui verifikasi produk ini?')) {
            form.submit();
        }
    }
}
