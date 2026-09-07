(function() {
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

    function openDigitalProductWizard() {
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
            const nameSpan = document.getElementById('dpDeliverableFileName');
            if (preview && nameSpan) {
                preview.style.display = 'flex';
                nameSpan.innerText = dpFormState.existingPlatformFile.split('/').pop();
            }
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

        if (dpFormState.existingPlatformFile && dpFormState.deliverableType === 'upload') {
            const preview = document.getElementById('dpDeliverableFilePreview');
            const nameEl = document.getElementById('dpDeliverableFileName');
            nameEl.innerText = dpFormState.existingPlatformFile.split('/').pop() + " (Sudah diupload)";
            preview.style.display = 'flex';
        } else {
            document.getElementById('dpDeliverableFilePreview').style.display = 'none';
        }

        document.getElementById('dpEnableSchedule').checked = dpFormState.isScheduled;
        document.getElementById('dpStartTime').value = dpFormState.startTime;
        document.getElementById('dpEndTime').value = dpFormState.endTime;
        toggleDpSchedule(dpFormState.isScheduled);

        updateDigitalProductWizardUI();
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

    function handleDpDeliverableFile(input) {
        if (input.files && input.files[0]) {
            dpFormState.deliverableFile = input.files[0];
            document.getElementById('dpDeliverableFileName').textContent = dpFormState.deliverableFile.name;
            document.getElementById('dpDeliverableFilePreview').style.display = 'flex';
        }
    }

    function removeDpDeliverableFile() {
        dpFormState.deliverableFile = null;
        dpFormState.existingPlatformFile = null;
        document.getElementById('dpDeliverableFile').value = '';
        document.getElementById('dpDeliverableFilePreview').style.display = 'none';
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
                if (!dpFormState.deliverableFile) {
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

        // Send via fetch
        fetch('{{ route('admin.elements.digital-product.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(async res => {
            if (!res.ok) {
                const errorData = await res.json().catch(() => ({}));
                if (res.status === 422 && errorData.errors) {
                    const errorMessages = Object.values(errorData.errors).flat().join('\\n');
                    throw new Error(errorMessages);
                }
                throw new Error(errorData.message || 'Gagal menghubungi server. Status: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                alert('Produk digital berhasil disimpan!');
                window.location.reload();
            } else {
                throw new Error('Terjadi kesalahan saat menyimpan produk.');
            }
        })
        .catch(err => {
            console.error(err);
            alert(err.message || 'Gagal menghubungi server.');
            if (btnSave) {
                btnSave.disabled = false;
                btnSave.innerHTML = 'Selesai';
            }
        });

        // 4. Reset & Kembalikan UI
        // cancelDigitalProductWizard already handles resetting the state and closing the wizard
        cancelDigitalProductWizard();
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
    window.openDigitalProductWizard = openDigitalProductWizard;
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
