(function() {
    window.MicrositeBuilder = window.MicrositeBuilder || {};
// State management for Digital Product Wizard
    let currentDpStep = 1;
    const maxDpStep = 3;

    // Form Local State
    let dpFormState = {
        element_id: null,
        title: '',
        description: '',
        files: [], // Media
        deliverableType: 'upload', // 'upload', 'gdrive', 'external'
        deliverableFile: null,
        deliverableUrl: '',
        priceType: 'fixed', // 'fixed', 'pwyw'
        priceFixed: 0,
        priceMin: 0,
        priceMax: '',
        qtyMin: 1,
        qtyMax: '',
        isScheduled: false,
        startTime: '',
        endTime: ''
    };

    // ============================================================
    // MODE SELECTOR: "Pilih dari Toko" vs "Buat Baru"
    // ============================================================

    let currentDpMode = null; // 'pick' | 'create'

    function switchDpMode(mode) {
        currentDpMode = mode;

        const modePickBtn   = document.getElementById('dpModePickBtn');
        const modeCreateBtn = document.getElementById('dpModeCreateBtn');
        const pickPanel     = document.getElementById('dpPickFromTokoPanel');
        const createPanel   = document.getElementById('dpCreatePanel');
        const btnNext       = document.getElementById('btn-dp-next');
        const btnPrev       = document.getElementById('btn-dp-prev');

        if (mode === 'pick') {
            if (modePickBtn) {
                modePickBtn.style.borderColor = '#ED842C';
                modePickBtn.style.background = '#fff7ed';
            }
            if (modeCreateBtn) {
                modeCreateBtn.style.borderColor = '#e5e7eb';
                modeCreateBtn.style.background = '#fff';
            }
            if (pickPanel) pickPanel.style.display    = 'block';
            if (createPanel) createPanel.style.display  = 'none';
            if (btnNext) btnNext.style.display      = 'none';
            if (btnPrev) btnPrev.style.display      = 'none';

            // Render list produk dari Toko
            renderTokoProductList(window._tokoProducts || []);
        } else {
            if (modeCreateBtn) {
                modeCreateBtn.style.borderColor = '#ED842C';
                modeCreateBtn.style.background = '#fff7ed';
            }
            if (modePickBtn) {
                modePickBtn.style.borderColor = '#e5e7eb';
                modePickBtn.style.background = '#fff';
            }
            if (pickPanel) pickPanel.style.display    = 'none';
            if (createPanel) createPanel.style.display  = 'block';
            if (btnNext) btnNext.style.display      = 'inline-flex';
        }
    }

    window.switchDpMode = switchDpMode;
    window.MicrositeBuilder.switchDpMode = switchDpMode;

    // Delegated click listener so clicking mode cards always responds immediately
    document.addEventListener('click', function(e) {
        const pickBtn = e.target.closest('#dpModePickBtn');
        if (pickBtn) {
            e.preventDefault();
            switchDpMode('pick');
            return;
        }
        const createBtn = e.target.closest('#dpModeCreateBtn');
        if (createBtn) {
            e.preventDefault();
            switchDpMode('create');
            return;
        }
    });

    function renderTokoProductList(products) {
        const list     = document.getElementById('dpPickProductList');
        const emptyMsg = document.getElementById('dpPickEmptyMsg');
        const search   = (document.getElementById('dpPickSearch')?.value || '').toLowerCase().trim();

        if (!list || !emptyMsg) return;

        const filtered = (products || []).filter(p =>
            (p.title || '').toLowerCase().includes(search)
        );

        list.innerHTML = '';

        if (filtered.length === 0) {
            emptyMsg.style.display = 'block';
            list.style.display     = 'none';
            return;
        }

        emptyMsg.style.display = 'none';
        list.style.display     = 'flex';

        // Dapatkan produk yang sudah di-pin ke microsite ini
        const urlsEl      = document.getElementById('micrositeEditorUrls');
        const blocksOrder = (urlsEl?.dataset?.appearanceBlocksOrder || '').split(',');
        const pinnedIds   = blocksOrder
            .filter(b => b.startsWith('digitalproduct_'))
            .map(b => parseInt(b.replace('digitalproduct_', '')));

        filtered.forEach(product => {
            const isPinned = pinnedIds.includes(product.id);

            const card = document.createElement('div');
            card.style.cssText = `
                display: flex; align-items: center; gap: 12px;
                padding: 10px 12px; border-radius: 8px;
                border: 1px solid ${isPinned ? '#fed7aa' : '#e5e7eb'};
                background: ${isPinned ? '#fff7ed' : '#fff'};
                cursor: ${isPinned ? 'default' : 'pointer'};
                transition: all 0.15s;
            `;

            if (!isPinned) {
                card.onmouseover = () => { card.style.borderColor = '#ED842C'; card.style.background = '#fff7ed'; };
                card.onmouseout  = () => { card.style.borderColor = '#e5e7eb'; card.style.background = '#fff'; };
            }

            // Gambar produk
            const imgWrap = document.createElement('div');
            imgWrap.style.cssText = 'width: 44px; height: 44px; border-radius: 6px; overflow: hidden; flex-shrink: 0; background: #f1f5f9; display: flex; align-items: center; justify-content: center;';
            if (product.image) {
                const img = document.createElement('img');
                img.src   = product.image;
                img.alt   = product.title;
                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
                imgWrap.appendChild(img);
            } else {
                imgWrap.innerHTML = '<i class="fas fa-box" style="color: #ED842C; font-size: 18px;"></i>';
            }
            card.appendChild(imgWrap);

            // Info produk
            const info = document.createElement('div');
            info.style.flex = '1';
            info.style.minWidth = '0';
            info.innerHTML = `
                <div style="font-size: 13px; font-weight: 600; color: #0f172a; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${product.title}</div>
                <div style="font-size: 12px; color: #64748b;">${product.price}</div>
            `;
            card.appendChild(info);

            // Tombol / Status
            const action = document.createElement('div');
            action.style.flexShrink = '0';
            if (isPinned) {
                action.innerHTML = '<span style="font-size: 11px; color: #ED842C; font-weight: 700; background: #fed7aa; padding: 4px 8px; border-radius: 20px;">Sudah ada</span>';
            } else {
                const addBtn = document.createElement('button');
                addBtn.type = 'button';
                addBtn.innerHTML = '<i class="fas fa-plus"></i> Tambah';
                addBtn.style.cssText = 'padding: 6px 12px; background: #ED842C; color: #fff; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;';
                addBtn.onclick = (e) => {
                    e.stopPropagation();
                    pinTokoProductToMicrosite(product, addBtn);
                };
                action.appendChild(addBtn);
            }
            card.appendChild(action);

            list.appendChild(card);
        });
    }

    window.filterTokoProducts = function(query) {
        renderTokoProductList(window._tokoProducts || []);
    };

    function pinTokoProductToMicrosite(product, btn) {
        const urlsEl       = document.getElementById('micrositeEditorUrls');
        const appearanceId = urlsEl?.dataset?.appearanceId;
        const pinUrl       = urlsEl?.dataset?.routeDpPin;

        if (!appearanceId || !pinUrl) {
            alert('Tidak dapat menemukan microsite aktif.');
            return;
        }

        btn.disabled  = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(pinUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                product_id:    product.id,
                appearance_id: appearanceId,
            }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menambahkan produk.');
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-plus"></i> Tambah';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menghubungi server.');
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Tambah';
        });
    }

    // Initialize Quill Editor
    let dpQuill;
    
    function initDpQuill() {
        const editorElement = document.getElementById('dpDescriptionEditor');
        if (!editorElement) return;
        
        // Cek apakah Quill sudah diinisialisasi sebelumnya untuk mencegah duplikasi toolbar
        if (editorElement.previousSibling && editorElement.previousSibling.classList && editorElement.previousSibling.classList.contains('ql-toolbar')) {
            return; 
        }

        var toolbarOptions = [
            [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
            ['bold', 'italic', 'underline'],
            [{ 'background': [] }], // Highlight Color
            [{ 'list': 'bullet' }, { 'list': 'ordered' }],
            [{ 'align': [] }],
            ['link']
        ];

        dpQuill = new Quill('#dpDescriptionEditor', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Tuliskan deskripsi lengkap produk digital Anda...'
        });

        // Listen for changes and update state
        dpQuill.on('text-change', function(delta, oldDelta, source) {
            let text = dpQuill.getText().trim();
            let words = text === '' ? [] : text.split(/\s+/).filter(word => word.length > 0);
            
            if (words.length > 250) {
                // Approximate character limit based on 250 words
                // Quill's deleteText requires an index and length
                // We'll just show a toast, as truncating exact words in Quill delta is complex
                if (typeof showToast === 'function') {
                    showToast('Maksimal 250 kata untuk deskripsi', 'warning');
                }
            }
            dpFormState.description = dpQuill.root.innerHTML;
        });
    }

    document.addEventListener("DOMContentLoaded", initDpQuill);
    document.addEventListener("turbo:load", initDpQuill);

    window.MicrositeBuilder.openDigitalProductWizard = function() {
        // Hide add element panel
        document.getElementById('addElementPanel').classList.remove('show');
        const btnToggleIcon = document.getElementById('btnToggleIcon');
        if(btnToggleIcon) {
            btnToggleIcon.style.transform = 'rotate(0deg)';
        }

        // Hide main editor panels
        document.getElementById('editorPanelElemen').style.display = 'none';
        
        // Tab header might need to be hidden or disabled. For now, just hide the tab content.
        const tabHeader = document.querySelector('.editor-panel-tab-switcher');
        if(tabHeader) tabHeader.style.display = 'none';

        // Reset step and state
        currentDpStep = 1;
        
        // Reset state
        dpFormState.element_id = null;
        dpFormState.title = '';
        dpFormState.description = '';
        dpFormState.files = [];
        dpFormState.existingFiles = [];
        dpFormState.existingPlatformFile = null;
        dpFormState.deliverableType = 'upload';
        dpFormState.deliverableFile = null;
        dpFormState.deliverableUrl = '';
        dpFormState.priceType = 'fixed';
        dpFormState.priceFixed = '';
        dpFormState.priceMin = '';
        dpFormState.priceMax = '';
        dpFormState.qtyMin = 1;
        dpFormState.qtyMax = '';
        dpFormState.isScheduled = false;
        dpFormState.startTime = '';
        dpFormState.endTime = '';
        
        // Reset UI inputs
        document.getElementById('dpTitle').value = '';
        if (dpQuill) {
            dpQuill.setContents([]);
        }
        document.getElementById('dpFilesError').style.display = 'none';
        renderDpFilePreviews();
        
        document.querySelector('input[name="dpDeliverableType"][value="upload"]').checked = true;
        document.getElementById('dpDeliverableUrl').value = '';
        document.getElementById('dpDeliverableFile').value = '';
        document.getElementById('dpDeliverableFilePreview').style.display = 'none';
        changeDpDeliverableType('upload');

        document.querySelector('input[name="dpPriceType"][value="fixed"]').checked = true;
        document.getElementById('dpFixedPrice').value = '';
        document.getElementById('dpMinPrice').value = '';
        document.getElementById('dpMaxPrice').value = '';
        document.getElementById('dpMinQty').value = 1;
        document.getElementById('dpMaxQty').value = '';
        changeDpPriceType('fixed');

        document.getElementById('dpEnableSchedule').checked = false;
        document.getElementById('dpStartTime').value = '';
        document.getElementById('dpEndTime').value = '';
        toggleDpSchedule(false);

        updateDigitalProductWizardUI();

        // Reset mode selector
        currentDpMode = null;
        const modeSelector = document.getElementById('dpModeSelector');
        if (modeSelector) modeSelector.style.display = 'block';
        const pickPanel = document.getElementById('dpPickFromTokoPanel');
        if (pickPanel) pickPanel.style.display = 'none';
        const createPanel = document.getElementById('dpCreatePanel');
        if (createPanel) createPanel.style.display = 'none';
        const pickSearch = document.getElementById('dpPickSearch');
        if (pickSearch) pickSearch.value = '';
        const btnNext = document.getElementById('btn-dp-next');
        if (btnNext) btnNext.style.display = 'none';
        const btnPrev = document.getElementById('btn-dp-prev');
        if (btnPrev) btnPrev.style.display = 'none';

        ['dpModePickBtn', 'dpModeCreateBtn'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.borderColor = '#e5e7eb';
                el.style.background = '#fff';
            }
        });

        // Show wizard
        document.getElementById('digitalProductWizardPanel').style.display = 'block';
    }

    function openEditDigitalProductWizard(product) {
        // Hide main panels
        const tabHeader = document.querySelector('.editor-panel-tab-switcher');
        if(tabHeader) tabHeader.style.display = 'none';
        document.getElementById('editorPanelElemen').style.display = 'none';
        
        currentDpStep = 1;
        
        // Populate state
        dpFormState.element_id = product.id;
        dpFormState.title = product.title || '';
        dpFormState.description = product.description || '';
        dpFormState.files = []; 
        
        let existingMedia = [];
        if (product.media_files) {
            existingMedia = typeof product.media_files === 'string' ? JSON.parse(product.media_files) : product.media_files;
        } else if (product.image) {
            existingMedia = [{url: '/storage/' + product.image}];
        }
        dpFormState.existingFiles = existingMedia;
        dpFormState.existingPlatformFile = product.platform_file || product.deliverable_url || null;
        
        dpFormState.deliverableType = product.deliverable_type || 'upload';
        dpFormState.deliverableFile = null;
        dpFormState.deliverableUrl = product.deliverable_url || '';
        
        if (dpFormState.deliverableType === 'upload' && dpFormState.existingPlatformFile && dpFormState.existingPlatformFile !== '') {
            const preview = document.getElementById('dpDeliverableFilePreview');
            const nameEl = document.getElementById('dpDeliverableFileName');
            const sizeEl = document.getElementById('dpDeliverableFileSize');
            const iconWrapper = document.getElementById('dpFileIconWrapper');
            const iconEl = document.getElementById('dpFileIcon');
            const statusBadge = document.getElementById('dpFileStatusBadge');
            const badgeDot = document.getElementById('dpFileBadgeDot');
            const statusText = document.getElementById('dpFileStatusText');
            const progressFill = document.getElementById('dpFileProgressFill');

            const fileName = dpFormState.existingPlatformFile.split('/').pop();
            if (nameEl) nameEl.textContent = fileName;
            if (sizeEl) sizeEl.textContent = 'File Tersimpan di Server';
            if (iconEl) iconEl.className = 'fas ' + getDpFileIconClass(fileName);

            if (iconWrapper) iconWrapper.classList.add('success');
            if (statusBadge) statusBadge.classList.add('success');
            if (badgeDot) badgeDot.classList.add('success');
            if (progressFill) {
                progressFill.style.width = '100%';
                progressFill.classList.add('success');
            }
            if (statusText) statusText.textContent = 'Terupload (100%)';

            if (preview) preview.style.display = 'block';
        } else {
            const preview = document.getElementById('dpDeliverableFilePreview');
            if (preview) preview.style.display = 'none';
        }
        dpFormState.priceType = product.pricing_type || 'fixed';
        dpFormState.priceFixed = product.price || '';
        dpFormState.priceMin = product.price_min || '';
        dpFormState.priceMax = product.price_max || '';
        dpFormState.qtyMin = product.quantity_min || 1;
        dpFormState.qtyMax = product.has_quantity_limit ? product.quantity : '';
        dpFormState.isScheduled = product.is_scheduled ? true : false;
        dpFormState.startTime = product.start_time ? product.start_time.substring(0, 16) : ''; // format YYYY-MM-DDThh:mm
        dpFormState.endTime = product.end_time ? product.end_time.substring(0, 16) : '';
        
        // Populate UI
        document.getElementById('dpTitle').value = dpFormState.title;
        if (dpQuill) {
            dpQuill.root.innerHTML = dpFormState.description;
        }
        renderDpFilePreviews();
        
        document.querySelector(`input[name="dpDeliverableType"][value="${dpFormState.deliverableType}"]`).checked = true;
        document.getElementById('dpDeliverableUrl').value = dpFormState.deliverableUrl;
        changeDpDeliverableType(dpFormState.deliverableType);

        document.querySelector(`input[name="dpPriceType"][value="${dpFormState.priceType}"]`).checked = true;
        document.getElementById('dpFixedPrice').value = formatNumberWithDot(dpFormState.priceFixed);
        document.getElementById('dpMinPrice').value = formatNumberWithDot(dpFormState.priceMin);
        document.getElementById('dpMaxPrice').value = formatNumberWithDot(dpFormState.priceMax);
        document.getElementById('dpMinQty').value = dpFormState.qtyMin;
        document.getElementById('dpMaxQty').value = dpFormState.qtyMax;
        changeDpPriceType(dpFormState.priceType);
        changeDpQtyLimitType(product.has_quantity_limit ? 'limited' : 'unlimited');

        document.getElementById('dpEnableSchedule').checked = dpFormState.isScheduled;
        document.getElementById('dpStartTime').value = dpFormState.startTime;
        document.getElementById('dpEndTime').value = dpFormState.endTime;
        toggleDpSchedule(dpFormState.isScheduled);

        updateDigitalProductWizardUI();

        // In edit mode, bypass mode selector directly to create/edit form
        const modeSelector = document.getElementById('dpModeSelector');
        if (modeSelector) modeSelector.style.display = 'none';
        const pickPanel = document.getElementById('dpPickFromTokoPanel');
        if (pickPanel) pickPanel.style.display = 'none';
        const createPanel = document.getElementById('dpCreatePanel');
        if (createPanel) createPanel.style.display = 'block';
        const btnNext = document.getElementById('btn-dp-next');
        if (btnNext) btnNext.style.display = 'inline-flex';

        document.getElementById('digitalProductWizardPanel').style.display = 'block';
    }

    function cancelDigitalProductWizard() {
        // Hide wizard
        document.getElementById('digitalProductWizardPanel').style.display = 'none';

        // Show main editor panels
        document.getElementById('editorPanelElemen').style.display = 'block';
        
        // Show tab header again
        const tabHeader = document.querySelector('.editor-panel-tab-switcher');
        if(tabHeader) tabHeader.style.display = 'flex';
    }

    // Step 1: Form Handlers
    function updateDpTitle(val) {
        dpFormState.title = val;
    }

    function handleDpFiles(input) {
        const errorEl = document.getElementById('dpFilesError');
        errorEl.style.display = 'none';
        
        let newFiles = Array.from(input.files);
        
        // Validation: Max 5 files total
        if (dpFormState.files.length + newFiles.length > 5) {
            errorEl.textContent = 'Maksimal 5 file media yang diizinkan.';
            errorEl.style.display = 'block';
            
            // Allow adding up to the 5 limit
            const availableSlots = 5 - dpFormState.files.length;
            newFiles = newFiles.slice(0, availableSlots);
        }
        
        // Append valid files
        newFiles.forEach(file => {
            dpFormState.files.push(file);
        });
        
        // Clear input value so same file can trigger 'change' event again if removed
        input.value = '';
        
        renderDpFilePreviews();
    }

    function removeDpFile(index) {
        // Revoke Object URL to free memory if needed (optional but good practice)
        // URL.revokeObjectURL(dpFormState.files[index].previewUrl); 
        dpFormState.files.splice(index, 1);
        
        // Clear error if we go below limit
        const errorEl = document.getElementById('dpFilesError');
        if (dpFormState.files.length < 5) {
            errorEl.style.display = 'none';
        }

        renderDpFilePreviews();
    }

    function renderDpFilePreviews() {
        const container = document.getElementById('dpFilesPreview');
        container.innerHTML = ''; // Clear container
        
        // Render existing files first
        if (dpFormState.existingFiles && dpFormState.existingFiles.length > 0) {
            dpFormState.existingFiles.forEach((file, index) => {
                const item = document.createElement('div');
                item.style.position = 'relative';
                item.style.width = '80px';
                item.style.height = '80px';
                item.style.borderRadius = '8px';
                item.style.overflow = 'hidden';
                item.style.border = '1px solid #e2e8f0';
                item.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
                item.style.backgroundColor = '#f1f5f9';
                item.style.display = 'flex';
                item.style.alignItems = 'center';
                item.style.justifyContent = 'center';
                
                const img = document.createElement('img');
                img.src = file.url.startsWith('http') || file.url.startsWith('/') ? file.url : '/storage/' + file.url;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                item.appendChild(img);
                
                // Overlay text indicating existing
                const badge = document.createElement('div');
                badge.innerText = 'Tersimpan';
                badge.style.position = 'absolute';
                badge.style.bottom = '0';
                badge.style.width = '100%';
                badge.style.textAlign = 'center';
                badge.style.background = 'rgba(0,0,0,0.5)';
                badge.style.color = 'white';
                badge.style.fontSize = '10px';
                badge.style.padding = '2px 0';
                item.appendChild(badge);
                
                // Remove Button for Existing File
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '4px';
                removeBtn.style.right = '4px';
                removeBtn.style.background = 'rgba(239, 68, 68, 0.9)'; // Red-500
                removeBtn.style.color = 'white';
                removeBtn.style.border = 'none';
                removeBtn.style.borderRadius = '50%';
                removeBtn.style.width = '20px';
                removeBtn.style.height = '20px';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.display = 'flex';
                removeBtn.style.alignItems = 'center';
                removeBtn.style.justifyContent = 'center';
                removeBtn.onclick = () => {
                    dpFormState.existingFiles.splice(index, 1);
                    renderDpFilePreviews();
                };
                item.appendChild(removeBtn);
                
                container.appendChild(item);
            });
        }
        
        dpFormState.files.forEach((file, index) => {
            const item = document.createElement('div');
            item.style.position = 'relative';
            item.style.width = '80px';
            item.style.height = '80px';
            item.style.borderRadius = '8px';
            item.style.overflow = 'hidden';
            item.style.border = '1px solid #e2e8f0';
            item.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
            item.style.backgroundColor = '#f1f5f9';
            item.style.display = 'flex';
            item.style.alignItems = 'center';
            item.style.justifyContent = 'center';
            
            // Remove Button
            const removeBtn = document.createElement('button');
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.style.position = 'absolute';
            removeBtn.style.top = '4px';
            removeBtn.style.right = '4px';
            removeBtn.style.background = 'rgba(239, 68, 68, 0.9)'; // Red-500
            removeBtn.style.color = 'white';
            removeBtn.style.border = 'none';
            removeBtn.style.borderRadius = '50%';
            removeBtn.style.width = '20px';
            removeBtn.style.height = '20px';
            removeBtn.style.cursor = 'pointer';
            removeBtn.style.display = 'flex';
            removeBtn.style.alignItems = 'center';
            removeBtn.style.justifyContent = 'center';
            removeBtn.style.fontSize = '10px';
            removeBtn.style.zIndex = '10';
            removeBtn.onclick = (e) => {
                e.stopPropagation();
                removeDpFile(index);
            };
            
            // File Preview (Image vs Video)
            const objectUrl = URL.createObjectURL(file);
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = objectUrl;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                
                // Release object URL when loaded
                img.onload = () => URL.revokeObjectURL(objectUrl);
                item.appendChild(img);
            } else if (file.type === 'video/mp4') {
                const video = document.createElement('video');
                video.src = objectUrl;
                video.style.width = '100%';
                video.style.height = '100%';
                video.style.objectFit = 'cover';
                
                video.onloadeddata = () => URL.revokeObjectURL(objectUrl);
                item.appendChild(video);
                
                // Play Icon Overlay
                const playIcon = document.createElement('div');
                playIcon.innerHTML = '<i class="fas fa-play"></i>';
                playIcon.style.position = 'absolute';
                playIcon.style.color = 'rgba(255, 255, 255, 0.9)';
                playIcon.style.fontSize = '24px';
                playIcon.style.textShadow = '0px 2px 4px rgba(0,0,0,0.5)';
                item.appendChild(playIcon);
            } else {
                // Fallback icon for unsupported files (should be filtered by accept)
                const fileIcon = document.createElement('i');
                fileIcon.className = 'fas fa-file';
                fileIcon.style.fontSize = '24px';
                fileIcon.style.color = '#94a3b8';
                item.appendChild(fileIcon);
            }
            
            item.appendChild(removeBtn);
            container.appendChild(item);
        });
    }

    // Step 1: Deliverable Handlers
    function changeDpDeliverableType(type) {
        dpFormState.deliverableType = type;
        const uploadSection = document.getElementById('dpDeliverableUploadSection');
        const urlSection = document.getElementById('dpDeliverableUrlSection');
        const urlInput = document.getElementById('dpDeliverableUrl');

        if (type === 'upload') {
            uploadSection.style.display = 'block';
            urlSection.style.display = 'none';
        } else {
            uploadSection.style.display = 'none';
            urlSection.style.display = 'block';
            if (type === 'gdrive') {
                urlInput.placeholder = 'https://drive.google.com/...';
            } else {
                urlInput.placeholder = 'https://...';
            }
        }
    }

    function formatDpBytes(bytes, decimals = 1) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function getDpFileIconClass(filename) {
        if (!filename) return 'fa-file-alt';
        const ext = filename.split('.').pop().toLowerCase();
        switch (ext) {
            case 'pdf': return 'fa-file-pdf';
            case 'zip':
            case 'rar':
            case '7z':
            case 'tar':
            case 'gz': return 'fa-file-archive';
            case 'doc':
            case 'docx': return 'fa-file-word';
            case 'xls':
            case 'xlsx':
            case 'csv': return 'fa-file-excel';
            case 'ppt':
            case 'pptx': return 'fa-file-powerpoint';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp': return 'fa-file-image';
            case 'mp3':
            case 'wav':
            case 'm4a': return 'fa-file-audio';
            case 'mp4':
            case 'avi':
            case 'mov': return 'fa-file-video';
            default: return 'fa-file-alt';
        }
    }

    let dpFileSimTimer = null;
    function handleDpDeliverableFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            dpFormState.deliverableFile = file;

            const preview = document.getElementById('dpDeliverableFilePreview');
            const nameSpan = document.getElementById('dpDeliverableFileName');
            const sizeSpan = document.getElementById('dpDeliverableFileSize');
            const iconWrapper = document.getElementById('dpFileIconWrapper');
            const iconEl = document.getElementById('dpFileIcon');
            const statusBadge = document.getElementById('dpFileStatusBadge');
            const badgeDot = document.getElementById('dpFileBadgeDot');
            const statusText = document.getElementById('dpFileStatusText');
            const progressFill = document.getElementById('dpFileProgressFill');

            if (nameSpan) nameSpan.textContent = file.name;
            if (sizeSpan) sizeSpan.textContent = formatDpBytes(file.size);
            if (iconEl) iconEl.className = 'fas ' + getDpFileIconClass(file.name);

            if (preview) preview.style.display = 'block';

            // Animasi visual progress bar saat user memilih berkas
            if (iconWrapper) iconWrapper.classList.remove('success');
            if (statusBadge) statusBadge.classList.remove('success');
            if (badgeDot) badgeDot.classList.remove('success');
            if (progressFill) {
                progressFill.classList.remove('success');
                progressFill.style.width = '0%';
            }
            if (statusText) statusText.textContent = 'Memuat (0%)...';

            if (dpFileSimTimer) clearInterval(dpFileSimTimer);
            let progress = 0;
            dpFileSimTimer = setInterval(() => {
                progress += Math.floor(Math.random() * 25) + 15;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(dpFileSimTimer);
                    if (progressFill) {
                        progressFill.style.width = '100%';
                        progressFill.classList.add('success');
                    }
                    if (iconWrapper) iconWrapper.classList.add('success');
                    if (statusBadge) statusBadge.classList.add('success');
                    if (badgeDot) badgeDot.classList.add('success');
                    if (statusText) statusText.textContent = 'Terupload (100%)';
                } else {
                    if (progressFill) progressFill.style.width = progress + '%';
                    if (statusText) statusText.textContent = 'Memuat (' + progress + '%)...';
                }
            }, 40);
        }
    }

    function removeDpDeliverableFile() {
        dpFormState.deliverableFile = null;
        dpFormState.existingPlatformFile = null;
        const fileInput = document.getElementById('dpDeliverableFile');
        if (fileInput) fileInput.value = '';
        const preview = document.getElementById('dpDeliverableFilePreview');
        if (preview) preview.style.display = 'none';
    }

    function updateDpDeliverableUrl(val) {
        dpFormState.deliverableUrl = val;
    }

    // Step 2: Pricing Handlers
    function changeDpPriceType(type) {
        dpFormState.priceType = type;
        const fixedSection = document.getElementById('dpFixedPriceSection');
        const pwywSection = document.getElementById('dpPwywSection');
        
        if (type === 'fixed') {
            fixedSection.style.display = 'block';
            pwywSection.style.display = 'none';
        } else {
            fixedSection.style.display = 'none';
            pwywSection.style.display = 'block';
        }
    }

    function formatNumberWithDot(number) {
        if (number === null || number === undefined || number === '') return '';
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function formatRupiahInput(input, fieldType) {
        let rawValue = input.value.replace(/[^0-9]/g, '');
        
        if (rawValue === '') {
            input.value = '';
            updateDpPriceField(fieldType, '');
            return;
        }
        
        rawValue = parseInt(rawValue, 10).toString();
        let formattedValue = formatNumberWithDot(rawValue);
        
        input.value = formattedValue;
        updateDpPriceField(fieldType, rawValue);
    }

    function updateDpPriceField(field, value) {
        if (field === 'fixed') dpFormState.priceFixed = value;
        else if (field === 'min') dpFormState.priceMin = value;
        else if (field === 'max') dpFormState.priceMax = value;
    }

    function updateDpQtyField(field, value) {
        if (field === 'min') dpFormState.qtyMin = value;
        else if (field === 'max') dpFormState.qtyMax = value;
    }

    function changeDpQtyLimitType(type) {
        dpFormState.qtyLimitType = type;
        const qtyUnlimitedWrapper = document.getElementById('dp-qty-unlimited-wrapper');
        const qtyLimitedWrapper = document.getElementById('dp-qty-limited-wrapper');
        const maxQtySection = document.getElementById('dpMaxQtySection');
        
        if (type === 'unlimited') {
            qtyUnlimitedWrapper.classList.add('active');
            qtyLimitedWrapper.classList.remove('active');
            maxQtySection.style.display = 'none';
            dpFormState.qtyMax = ''; // Clear value
            document.getElementById('dpMaxQty').value = '';
        } else {
            qtyUnlimitedWrapper.classList.remove('active');
            qtyLimitedWrapper.classList.add('active');
            maxQtySection.style.display = 'block';
        }
    }

    // Step 3: Schedule Handlers
    function toggleDpSchedule(enabled) {
        dpFormState.isScheduled = enabled;
        document.getElementById('dpScheduleSection').style.display = enabled ? 'block' : 'none';
    }

    function updateDpScheduleField(field, value) {
        if (field === 'start') dpFormState.startTime = value;
        else if (field === 'end') dpFormState.endTime = value;
    }

    function nextDigitalProductStep() {
        // Step Validation
        if (currentDpStep === 1) {
            if (!dpFormState.title || dpFormState.title.trim() === '') {
                alert('Nama produk wajib diisi.');
                document.getElementById('dpTitle').focus();
                return;
            }
            if (!dpFormState.description || dpFormState.description.trim() === '') {
                alert('Deskripsi produk wajib diisi.');
                if (dpQuill) dpQuill.focus();
                return;
            }
            if (dpFormState.deliverableType === 'upload') {
                if (!dpFormState.deliverableFile && !dpFormState.existingPlatformFile) {
                    alert('Silakan unggah file isi produk yang akan dijual.');
                    return;
                }
            } else {
                if (!dpFormState.deliverableUrl || dpFormState.deliverableUrl.trim() === '') {
                    alert('Silakan masukkan URL tautan produk yang valid.');
                    document.getElementById('dpDeliverableUrl').focus();
                    return;
                }
            }
        } else if (currentDpStep === 2) {
            // Step 2 Validation
            if (dpFormState.priceType === 'fixed') {
                if (dpFormState.priceFixed === '' || isNaN(dpFormState.priceFixed) || parseFloat(dpFormState.priceFixed) < 0) {
                    alert('Silakan masukkan harga jual yang valid.');
                    document.getElementById('dpFixedPrice').focus();
                    return;
                }
            } else {
                if (dpFormState.priceMin === '' || isNaN(dpFormState.priceMin) || parseFloat(dpFormState.priceMin) < 0) {
                    alert('Silakan masukkan harga minimal yang valid.');
                    document.getElementById('dpMinPrice').focus();
                    return;
                }
                if (dpFormState.priceMax !== '' && parseFloat(dpFormState.priceMax) < parseFloat(dpFormState.priceMin)) {
                    alert('Harga maksimal tidak boleh lebih kecil dari harga minimal.');
                    document.getElementById('dpMaxPrice').focus();
                    return;
                }
            }
            
            if (dpFormState.qtyMin === '' || isNaN(dpFormState.qtyMin) || parseInt(dpFormState.qtyMin) < 1) {
                alert('Minimal pembelian harus minimal 1.');
                document.getElementById('dpMinQty').focus();
                return;
            }
            
            if (dpFormState.qtyMax !== '' && parseInt(dpFormState.qtyMax) < parseInt(dpFormState.qtyMin)) {
                alert('Maksimal pembelian tidak boleh lebih kecil dari minimal pembelian.');
                document.getElementById('dpMaxQty').focus();
                return;
            }
        } else if (currentDpStep === 3) {
            // Step 3 Validation
            if (dpFormState.isScheduled) {
                if (!dpFormState.startTime) {
                    alert('Silakan tentukan Waktu Mulai penayangan.');
                    document.getElementById('dpStartTime').focus();
                    return;
                }
                if (!dpFormState.endTime) {
                    alert('Silakan tentukan Waktu Berakhir penayangan.');
                    document.getElementById('dpEndTime').focus();
                    return;
                }
                
                // Validate if end time is after start time
                const start = new Date(dpFormState.startTime);
                const end = new Date(dpFormState.endTime);
                if (end <= start) {
                    alert('Waktu Berakhir harus lebih lambat dari Waktu Mulai.');
                    document.getElementById('dpEndTime').focus();
                    return;
                }
            }
        }

        if (currentDpStep < maxDpStep) {
            currentDpStep++;
            updateDigitalProductWizardUI();
        } else {
            // Reached the end, perform save/submit action
            handleSaveProduct();
        }
    }

    function handleSaveProduct() {
        // 1. Validasi Data
        if (!dpFormState.title || !dpFormState.description) {
            alert("Data produk belum lengkap. Silakan periksa kembali form Anda.");
            return;
        }

        const btnSave = document.getElementById('btn-dp-next');
        if (btnSave) {
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        }

        // 2. Siapkan FormData
        let formData = new FormData();
        if (dpFormState.element_id) {
            formData.append('element_id', dpFormState.element_id);
        }
        formData.append('title', dpFormState.title);
        formData.append('description', dpFormState.description);
        
        // Media files
        formData.append('media_count', dpFormState.files.length);
        dpFormState.files.forEach((file, index) => {
            formData.append(`media_${index}`, file);
        });

        // Pricing
        formData.append('pricing_type', dpFormState.priceType || 'fixed');
        if (dpFormState.priceType === 'fixed') {
            formData.append('price_fixed', dpFormState.priceFixed || 0);
        } else {
            formData.append('price_min', dpFormState.priceMin || 0);
            formData.append('price_max', dpFormState.priceMax || '');
        }

        // Quantity
        formData.append('quantity_min', dpFormState.qtyMin || 1);
        if (dpFormState.qtyMax) {
            formData.append('has_quantity_limit', 1);
            formData.append('quantity_max', dpFormState.qtyMax);
        } else {
            formData.append('has_quantity_limit', 0);
        }

        // Scheduling
        formData.append('is_scheduled', dpFormState.isScheduled ? 1 : 0);
        if (dpFormState.isScheduled) {
            formData.append('start_time', dpFormState.startTime || '');
            formData.append('end_time', dpFormState.endTime || '');
        }

        // Deliverable
        formData.append('deliverable_type', dpFormState.deliverableType || 'upload');
        if (dpFormState.deliverableType === 'upload') {
            if (dpFormState.deliverableFile) {
                formData.append('deliverable_file', dpFormState.deliverableFile);
            } else if (!dpFormState.existingPlatformFile) {
                formData.append('remove_deliverable_file', 1);
            }
        } else if (dpFormState.deliverableUrl) {
            formData.append('deliverable_url', dpFormState.deliverableUrl);
        }

        // Send existing media
        if (dpFormState.existingFiles) {
            formData.append('existing_media', JSON.stringify(dpFormState.existingFiles));
        }

        // Add appearance_id so the controller knows which microsite to attach this to
        const urlsEl = document.getElementById('micrositeEditorUrls');
        if (urlsEl && urlsEl.dataset.appearanceId) {
            formData.append('appearance_id', urlsEl.dataset.appearanceId);
        }

        let storeUrl = '/admin/elements/digital-product';
        if (urlsEl && urlsEl.dataset.routeDpStore) {
            storeUrl = urlsEl.dataset.routeDpStore;
        }
        
        // Tampilkan modal progress upload
        const modal = document.getElementById('dpSubmitUploadModal');
        const modalFill = document.getElementById('dpSubmitModalProgressFill');
        const modalPercent = document.getElementById('dpSubmitModalPercentText');
        const modalBytes = document.getElementById('dpSubmitModalBytesText');
        const modalDesc = document.getElementById('dpSubmitModalDesc');

        if (modal) modal.style.display = 'flex';
        if (modalFill) modalFill.style.width = '0%';
        if (modalPercent) modalPercent.textContent = '0%';
        if (modalBytes) modalBytes.textContent = '0 B / 0 B';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', storeUrl, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfMeta.getAttribute('content'));
        }

        xhr.upload.addEventListener('progress', function(event) {
            if (event.lengthComputable) {
                const percent = Math.min(100, Math.round((event.loaded / event.total) * 100));
                if (modalFill) modalFill.style.width = percent + '%';
                if (modalPercent) modalPercent.textContent = percent + '%';
                if (modalBytes) {
                    modalBytes.textContent = formatDpBytes(event.loaded) + ' / ' + formatDpBytes(event.total);
                }
                if (percent === 100 && modalDesc) {
                    modalDesc.textContent = 'Sedang memproses dan menyimpan produk di server...';
                }
            }
        });

        xhr.addEventListener('load', function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                let data = {};
                try {
                    data = JSON.parse(xhr.responseText);
                } catch(e) {}

                if (modalFill) modalFill.style.width = '100%';
                if (modalPercent) modalPercent.textContent = '100%';

                setTimeout(() => {
                    if (modal) modal.style.display = 'none';
                    alert('Produk digital berhasil disimpan!');
                    cancelDigitalProductWizard();
                    window.location.reload();
                }, 300);
            } else {
                if (modal) modal.style.display = 'none';
                if (btnSave) {
                    btnSave.disabled = false;
                    btnSave.innerHTML = 'Selesai';
                }
                let errorMsg = 'Gagal menghubungi server. Status: ' + xhr.status;
                try {
                    const errorData = JSON.parse(xhr.responseText);
                    if (xhr.status === 422 && errorData.errors) {
                        errorMsg = Object.values(errorData.errors).flat().join('\n');
                    } else if (errorData.message) {
                        errorMsg = errorData.message;
                    }
                } catch(e) {}
                alert(errorMsg);
            }
        });

        xhr.addEventListener('error', function() {
            if (modal) modal.style.display = 'none';
            if (btnSave) {
                btnSave.disabled = false;
                btnSave.innerHTML = 'Selesai';
            }
            alert('Gagal menghubungi server. Periksa koneksi internet Anda.');
        });

        xhr.send(formData);
    }

    function prevDigitalProductStep() {
        if (currentDpStep > 1) {
            currentDpStep--;
            updateDigitalProductWizardUI();
        }
    }

    function updateDigitalProductWizardUI() {
        // Hide all steps and update indicators
        for (let i = 1; i <= maxDpStep; i++) {
            const stepEl = document.getElementById(`dp-step-${i}`);
            const indicatorEl = document.getElementById(`dp-step-indicator-${i}`);
            const iconEl = document.getElementById(`dp-step-icon-${i}`);
            const numEl = document.getElementById(`dp-step-num-${i}`);
            
            if (stepEl) stepEl.style.display = 'none';
            
            if (indicatorEl) {
                // Remove legacy inline styles if any
                indicatorEl.style.color = '';
                indicatorEl.style.fontWeight = '';
                
                indicatorEl.classList.remove('active', 'completed');
                
                if (i < currentDpStep) {
                    indicatorEl.classList.add('completed');
                    if (iconEl) iconEl.style.display = 'inline-block';
                    if (numEl) numEl.style.display = 'none';
                } else if (i === currentDpStep) {
                    indicatorEl.classList.add('active');
                    if (iconEl) iconEl.style.display = 'none';
                    if (numEl) numEl.style.display = 'inline-block';
                } else {
                    if (iconEl) iconEl.style.display = 'none';
                    if (numEl) numEl.style.display = 'inline-block';
                }
            }
        }

        // Show current step
        const currentStepEl = document.getElementById(`dp-step-${currentDpStep}`);
        if (currentStepEl) currentStepEl.style.display = 'block';

        // Show/hide prev button
        const btnPrev = document.getElementById('btn-dp-prev');
        if (currentDpStep > 1) {
            btnPrev.style.display = 'block';
        } else {
            btnPrev.style.display = 'none';
        }

        // Change next button text to 'Selesai' on last step
        const btnNext = document.getElementById('btn-dp-next');
        if (currentDpStep === maxDpStep) {
            btnNext.textContent = 'Selesai';
            btnNext.style.background = '#10b981'; // Green color for complete
            btnNext.style.color = 'white';
        } else {
            btnNext.textContent = 'Next';
            btnNext.style.background = '#FF9040'; // Original brand color
            btnNext.style.color = 'white';
        }
    }

    // Expose functions to window
    window.switchDpMode = switchDpMode;
    window.filterTokoProducts = filterTokoProducts;
    window.pinTokoProductToMicrosite = pinTokoProductToMicrosite;
    if (window.MicrositeBuilder) {
        window.MicrositeBuilder.switchDpMode = switchDpMode;
    }
    window.openEditDigitalProductWizard = openEditDigitalProductWizard;
    window.cancelDigitalProductWizard = cancelDigitalProductWizard;
    window.updateDpTitle = updateDpTitle;
    window.handleDpFiles = handleDpFiles;
    window.removeDpFile = removeDpFile;
    window.changeDpDeliverableType = changeDpDeliverableType;
    window.handleDpDeliverableFile = handleDpDeliverableFile;
    window.removeDpDeliverableFile = removeDpDeliverableFile;
    window.updateDpDeliverableUrl = updateDpDeliverableUrl;
    window.changeDpPriceType = changeDpPriceType;
    window.formatRupiahInput = formatRupiahInput;
    window.updateDpQtyField = updateDpQtyField;
    window.changeDpQtyLimitType = changeDpQtyLimitType;
    window.toggleDpSchedule = toggleDpSchedule;
    window.updateDpScheduleField = updateDpScheduleField;
    window.nextDigitalProductStep = nextDigitalProductStep;
    window.prevDigitalProductStep = prevDigitalProductStep;

})();
