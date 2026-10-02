/**
 * Digital Product Form Manager (digital-product-form.js)
 * Linkan.ID - Handles multi-step wizard, Quill editor, photo gallery, and XHR upload progress.
 */
(function () {
    'use strict';

    const ProductForm = {
        config: {
            maxPhotos: 5,
            allowedImageTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
            maxImageSize: 5 * 1024 * 1024, // 5MB
            indexUrl: '/admin-seller/digital-products',
            selectFileText: 'Pilih File',
            hasExistingPlatformFile: false,
            cleanDescription: '',
            existingPhotos: []
        },

        state: {
            existingPhotos: [],
            stagedFiles: [],
            currentStep: 1,
            quillInstance: null,
            platformFileTimer: null,
            originalSubmitBtnHtml: ''
        },

        helpers: {
            formatRupiah: function (angka) {
                const number_string = (angka || '').toString().replace(/[^,\d]/g, '');
                const split = number_string.split(',');
                const sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    const separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return 'Rp ' + rupiah;
            },

            unformatRupiah: function (rupiah) {
                return (rupiah || '').toString().replace(/[^\d]/g, '');
            },

            formatBytes: function (bytes, decimals) {
                if (decimals === undefined) decimals = 1;
                if (!bytes || bytes === 0) return '0 B';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            },

            getFileIconClass: function (filename) {
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
        },

        price: {
            init: function () {
                const priceInput = document.getElementById('priceInput');
                const priceRaw = document.getElementById('priceRaw');
                if (!priceInput) return;

                priceInput.addEventListener('input', function (e) {
                    const value = e.target.value;
                    const unformatted = ProductForm.helpers.unformatRupiah(value);

                    if (unformatted !== '') {
                        e.target.value = ProductForm.helpers.formatRupiah(unformatted);
                        if (priceRaw) priceRaw.value = unformatted;
                    } else {
                        e.target.value = '';
                        if (priceRaw) priceRaw.value = '';
                    }
                    ProductForm.validation.validateStep2();
                });

                priceInput.addEventListener('blur', function (e) {
                    const value = e.target.value;
                    if (value === '' || value === 'Rp ') {
                        e.target.value = '';
                        if (priceRaw) priceRaw.value = '';
                    }
                    ProductForm.validation.validateStep2();
                });
            }
        },

        photos: {
            init: function () {
                ProductForm.state.existingPhotos = Array.isArray(ProductForm.config.existingPhotos)
                    ? [...ProductForm.config.existingPhotos]
                    : [];
                ProductForm.state.stagedFiles = [];

                const fileInput = document.getElementById('productPhotosInput');
                if (fileInput) {
                    fileInput.addEventListener('change', ProductForm.photos.handleFileChange);
                }

                ProductForm.photos.renderGallery();
            },

            getCount: function () {
                return ProductForm.state.existingPhotos.length + ProductForm.state.stagedFiles.length;
            },

            showError: function (msg) {
                const errorEl = document.getElementById('photoErrorMessage');
                if (!errorEl) return;
                errorEl.textContent = msg;
                errorEl.style.display = 'block';
                setTimeout(function () {
                    errorEl.style.display = 'none';
                }, 5000);
            },

            syncFileInput: function () {
                const fileInput = document.getElementById('productPhotosInput');
                if (!fileInput) return;
                try {
                    const dt = new DataTransfer();
                    ProductForm.state.stagedFiles.forEach(function (file) {
                        dt.items.add(file);
                    });
                    fileInput.files = dt.files;
                } catch (e) {
                    console.error('DataTransfer error:', e);
                }
            },

            syncExistingInput: function () {
                const existingInput = document.getElementById('existingMediaInput');
                if (!existingInput) return;
                existingInput.value = JSON.stringify(ProductForm.state.existingPhotos);
            },

            removeExistingPhoto: function (index) {
                if (index >= 0 && index < ProductForm.state.existingPhotos.length) {
                    ProductForm.state.existingPhotos.splice(index, 1);
                    ProductForm.photos.syncExistingInput();
                    ProductForm.photos.renderGallery();
                }
            },

            removeStagedPhoto: function (index) {
                if (index >= 0 && index < ProductForm.state.stagedFiles.length) {
                    ProductForm.state.stagedFiles.splice(index, 1);
                    ProductForm.photos.syncFileInput();
                    ProductForm.photos.renderGallery();
                }
            },

            handleFileChange: function (e) {
                const fileInput = e.target;
                const selectedFiles = Array.from(fileInput.files || []);
                if (selectedFiles.length === 0) return;

                const currentTotal = ProductForm.photos.getCount();
                const availableSlots = ProductForm.config.maxPhotos - currentTotal;

                if (availableSlots <= 0) {
                    ProductForm.photos.showError('Maksimal ' + ProductForm.config.maxPhotos + ' foto produk sudah tercapai.');
                    fileInput.value = '';
                    return;
                }

                let toProcess = selectedFiles;
                if (toProcess.length > availableSlots) {
                    ProductForm.photos.showError(
                        'Hanya dapat menambahkan ' + availableSlots + ' foto lagi. ' +
                        (toProcess.length - availableSlots) + ' foto dilewati.'
                    );
                    toProcess = toProcess.slice(0, availableSlots);
                }

                toProcess.forEach(function (file) {
                    if (!ProductForm.config.allowedImageTypes.includes(file.type.toLowerCase())) {
                        ProductForm.photos.showError('Format file ' + file.name + ' tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                        return;
                    }
                    if (file.size > ProductForm.config.maxImageSize) {
                        ProductForm.photos.showError('Ukuran file ' + file.name + ' melebihi batas 5MB.');
                        return;
                    }
                    ProductForm.state.stagedFiles.push(file);
                });

                ProductForm.photos.syncFileInput();
                ProductForm.photos.renderGallery();
            },

            renderGallery: function () {
                const gridEl = document.getElementById('productPhotosGrid');
                const dropzoneEl = document.getElementById('photoEmptyDropzone');
                const fileInput = document.getElementById('productPhotosInput');
                if (!gridEl || !dropzoneEl) return;

                const totalPhotos = ProductForm.photos.getCount();

                if (totalPhotos === 0) {
                    gridEl.innerHTML = '';
                    gridEl.style.display = 'none';
                    dropzoneEl.style.display = 'block';
                } else {
                    dropzoneEl.style.display = 'none';
                    gridEl.style.display = 'grid';
                    gridEl.innerHTML = '';

                    let globalIndex = 0;

                    // Render existing photos
                    ProductForm.state.existingPhotos.forEach(function (item, idx) {
                        const isCover = (globalIndex === 0);
                        const card = document.createElement('div');
                        card.className = 'photo-item-card' + (isCover ? ' is-cover' : '');

                        const img = document.createElement('img');
                        img.src = item.url;
                        img.alt = 'Foto Produk';
                        card.appendChild(img);

                        if (isCover) {
                            const badge = document.createElement('span');
                            badge.className = 'photo-cover-badge';
                            badge.innerHTML = '<i class="fas fa-star"></i> Sampul';
                            card.appendChild(badge);
                        }

                        const delBtn = document.createElement('button');
                        delBtn.type = 'button';
                        delBtn.className = 'photo-btn-delete';
                        delBtn.title = 'Hapus foto ini';
                        delBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
                        delBtn.onclick = function (e) {
                            e.stopPropagation();
                            ProductForm.photos.removeExistingPhoto(idx);
                        };
                        card.appendChild(delBtn);

                        gridEl.appendChild(card);
                        globalIndex++;
                    });

                    // Render staged (newly selected) photos
                    ProductForm.state.stagedFiles.forEach(function (file, idx) {
                        const isCover = (globalIndex === 0);
                        const card = document.createElement('div');
                        card.className = 'photo-item-card' + (isCover ? ' is-cover' : '');

                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.alt = file.name;
                        img.onload = function () {
                            URL.revokeObjectURL(this.src);
                        };
                        card.appendChild(img);

                        if (isCover) {
                            const badge = document.createElement('span');
                            badge.className = 'photo-cover-badge';
                            badge.innerHTML = '<i class="fas fa-star"></i> Sampul';
                            card.appendChild(badge);
                        } else {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'photo-new-badge';
                            newBadge.textContent = 'Baru';
                            card.appendChild(newBadge);
                        }

                        const delBtn = document.createElement('button');
                        delBtn.type = 'button';
                        delBtn.className = 'photo-btn-delete';
                        delBtn.title = 'Hapus foto ini';
                        delBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
                        delBtn.onclick = function (e) {
                            e.stopPropagation();
                            ProductForm.photos.removeStagedPhoto(idx);
                        };
                        card.appendChild(delBtn);

                        gridEl.appendChild(card);
                        globalIndex++;
                    });

                    // If fewer than maxPhotos, show "+ Tambah Foto" card
                    if (totalPhotos < ProductForm.config.maxPhotos) {
                        const addCard = document.createElement('div');
                        addCard.className = 'photo-add-card';
                        addCard.innerHTML = '<i class="fas fa-plus"></i><span>Tambah Foto<br><small style="color:#94a3b8;">(' +
                            totalPhotos + '/' + ProductForm.config.maxPhotos + ')</small></span>';
                        addCard.onclick = function () {
                            if (fileInput) fileInput.click();
                        };
                        gridEl.appendChild(addCard);
                    }
                }

                ProductForm.validation.validateStep1();
            }
        },

        editor: {
            init: function () {
                const editorElement = document.getElementById('descriptionEditor');
                if (!editorElement || typeof Quill === 'undefined') return;

                if (editorElement.previousSibling && editorElement.previousSibling.classList &&
                    editorElement.previousSibling.classList.contains('ql-toolbar')) {
                    return;
                }

                const toolbarOptions = [
                    [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'background': [] }],
                    [{ 'list': 'bullet' }, { 'list': 'ordered' }],
                    [{ 'align': [] }],
                    ['link']
                ];

                const quill = new Quill('#descriptionEditor', {
                    theme: 'snow',
                    modules: {
                        toolbar: toolbarOptions
                    },
                    placeholder: 'Tuliskan deskripsi lengkap produk digital Anda...'
                });

                ProductForm.state.quillInstance = quill;

                const initialContent = ProductForm.config.cleanDescription;
                if (initialContent && initialContent.trim() !== '') {
                    if (/<[a-z][\s\S]*>/i.test(initialContent)) {
                        quill.clipboard.dangerouslyPasteHTML(initialContent);
                    } else {
                        quill.setText(initialContent);
                    }
                }

                quill.on('text-change', ProductForm.editor.syncContent);
                ProductForm.editor.syncContent();

                const form = document.getElementById('digitalProductForm');
                if (form) {
                    form.addEventListener('submit', ProductForm.editor.syncContent);
                }
            },

            isFilled: function () {
                const quill = ProductForm.state.quillInstance;
                const descInput = document.getElementById('descriptionInput');
                if (!quill) {
                    return !!(descInput && descInput.value.trim().length > 0);
                }
                return quill.getText().trim().length > 0;
            },

            syncContent: function () {
                const quill = ProductForm.state.quillInstance;
                const descInput = document.getElementById('descriptionInput');
                if (!descInput || !quill) return;

                const text = quill.getText().trim();
                if (text.length === 0) {
                    descInput.value = '';
                } else {
                    descInput.value = quill.root.innerHTML;
                }
                ProductForm.validation.validateStep1();
            }
        },

        platform: {
            init: function () {
                document.querySelectorAll('input[name="platform_type"]').forEach(function (radio) {
                    radio.addEventListener('change', ProductForm.platform.handleTypeChange);
                });

                const platformFileInput = document.getElementById('platform_file');
                if (platformFileInput) {
                    platformFileInput.addEventListener('change', ProductForm.platform.handleFileSelect);
                }
            },

            handleTypeChange: function () {
                document.querySelectorAll('.platform-card-wrapper').forEach(function (wrapper) {
                    wrapper.classList.remove('active');
                });

                if (this.checked) {
                    const wrapper = this.closest('.platform-card-wrapper');
                    if (wrapper) wrapper.classList.add('active');
                }

                const platform = this.value;
                const urlInput = document.getElementById('url-input-container');
                const fileButton = document.getElementById('file-input-container');

                if (platform === 'upload') {
                    if (urlInput) urlInput.style.display = 'none';
                    if (fileButton) fileButton.style.display = 'block';
                } else {
                    if (urlInput) urlInput.style.display = 'flex';
                    if (fileButton) fileButton.style.display = 'none';
                }

                ProductForm.validation.validateStep1();
            },

            handleFileSelect: function (e) {
                const file = e.target.files && e.target.files[0];
                const card = document.getElementById('platformFileProgressCard');
                const nameDisplay = document.getElementById('platformFileNameDisplay');
                const sizeDisplay = document.getElementById('platformFileSizeDisplay');
                const iconWrapper = document.getElementById('platformFileIconWrapper');
                const iconEl = document.getElementById('platformFileIcon');
                const statusBadge = document.getElementById('platformFileStatusBadge');
                const badgeDot = document.getElementById('platformFileBadgeDot');
                const statusText = document.getElementById('platformFileStatusText');
                const progressFill = document.getElementById('platformFileProgressFill');
                const displaySpan = document.getElementById('selected-file-name');
                const removeInput = document.getElementById('removePlatformFileInput');

                if (removeInput) removeInput.value = '0';

                if (file) {
                    const fileName = file.name;
                    const fileSize = ProductForm.helpers.formatBytes(file.size);
                    const iconClass = ProductForm.helpers.getFileIconClass(fileName);

                    if (displaySpan) displaySpan.textContent = fileName;
                    if (nameDisplay) nameDisplay.textContent = fileName;
                    if (sizeDisplay) sizeDisplay.textContent = fileSize;
                    if (iconEl) iconEl.className = 'fas ' + iconClass;

                    if (card) card.style.display = 'block';

                    if (iconWrapper) iconWrapper.classList.remove('success');
                    if (statusBadge) statusBadge.classList.remove('success');
                    if (badgeDot) badgeDot.classList.remove('success');
                    if (progressFill) {
                        progressFill.classList.remove('success');
                        progressFill.style.width = '0%';
                    }
                    if (statusText) statusText.textContent = 'Memuat (0%)...';

                    if (ProductForm.state.platformFileTimer) clearInterval(ProductForm.state.platformFileTimer);
                    let progress = 0;
                    ProductForm.state.platformFileTimer = setInterval(function () {
                        progress += Math.floor(Math.random() * 25) + 15;
                        if (progress >= 100) {
                            progress = 100;
                            clearInterval(ProductForm.state.platformFileTimer);
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
                } else {
                    if (card) card.style.display = 'none';
                }

                ProductForm.validation.validateStep1();
            },

            removeFile: function () {
                const fileInput = document.getElementById('platform_file');
                if (fileInput) fileInput.value = '';
                const removeInput = document.getElementById('removePlatformFileInput');
                if (removeInput) removeInput.value = '1';
                const card = document.getElementById('platformFileProgressCard');
                if (card) card.style.display = 'none';
                const displaySpan = document.getElementById('selected-file-name');
                if (displaySpan) displaySpan.textContent = ProductForm.config.selectFileText;

                ProductForm.validation.validateStep1();
            }
        },

        validation: {
            init: function () {
                const titleInput = document.querySelector('input[name="title"]');
                if (titleInput) {
                    titleInput.addEventListener('input', ProductForm.validation.validateStep1);
                    titleInput.addEventListener('change', ProductForm.validation.validateStep1);
                }

                const urlInput = document.querySelector('input[name="platform_url"]');
                if (urlInput) {
                    urlInput.addEventListener('input', ProductForm.validation.validateStep1);
                    urlInput.addEventListener('change', ProductForm.validation.validateStep1);
                }
            },

            validateStep1: function () {
                const btnNext = document.getElementById('btnNextStep1');
                if (!btnNext) return false;

                // 1. Judul Produk
                const titleInput = document.querySelector('input[name="title"]');
                const isTitleValid = !!(titleInput && titleInput.value.trim().length > 0);

                // 2. Deskripsi Produk
                const isDescValid = ProductForm.editor.isFilled();

                // 3. Foto Produk: minimal 1 foto
                const isPhotosValid = ProductForm.photos.getCount() > 0;

                // 4. File / URL Platform
                const checkedRadio = document.querySelector('input[name="platform_type"]:checked');
                const platform = checkedRadio ? checkedRadio.value : 'upload';
                let isPlatformValid = false;

                if (platform === 'upload') {
                    const fileInput = document.getElementById('platform_file');
                    const hasNewFile = !!(fileInput && fileInput.files && fileInput.files.length > 0);
                    const removeInput = document.getElementById('removePlatformFileInput');
                    const isRemoved = removeInput && removeInput.value === '1';
                    const hasExistingFile = (!isRemoved) && ProductForm.config.hasExistingPlatformFile;
                    isPlatformValid = hasNewFile || hasExistingFile;
                } else {
                    const urlInput = document.querySelector('input[name="platform_url"]');
                    isPlatformValid = !!(urlInput && urlInput.value.trim().length > 0);
                }

                const isValid = isTitleValid && isDescValid && isPhotosValid && isPlatformValid;
                btnNext.disabled = !isValid;

                if (isValid) {
                    btnNext.classList.remove('disabled');
                    btnNext.removeAttribute('title');
                } else {
                    btnNext.classList.add('disabled');
                    btnNext.setAttribute('title', 'Lengkapi semua kolom input (Judul, Deskripsi, Foto, & File/URL) terlebih dahulu');
                }

                return isValid;
            },

            validateStep2: function () {
                const btnAdd = document.getElementById('btnAddProduct');
                if (!btnAdd) return false;

                const priceRaw = document.getElementById('priceRaw');
                const priceInput = document.getElementById('priceInput');
                let rawVal = priceRaw ? priceRaw.value.trim() : '';
                if (rawVal === '' && priceInput) {
                    rawVal = ProductForm.helpers.unformatRupiah(priceInput.value).trim();
                }

                const isPriceValid = (rawVal !== '' && !isNaN(rawVal));
                btnAdd.disabled = !isPriceValid;

                if (isPriceValid) {
                    btnAdd.classList.remove('disabled');
                    btnAdd.removeAttribute('title');
                } else {
                    btnAdd.classList.add('disabled');
                    btnAdd.setAttribute('title', 'Masukkan harga produk terlebih dahulu');
                }

                return isPriceValid;
            }
        },

        stepper: {
            nextStep: function () {
                if (!ProductForm.validation.validateStep1()) {
                    return;
                }

                const step1Content = document.getElementById('step1-content');
                const step2Content = document.getElementById('step2-content');
                if (step1Content) step1Content.style.display = 'none';
                if (step2Content) step2Content.style.display = 'block';

                const step1Indicator = document.getElementById('step1-indicator');
                if (step1Indicator) {
                    step1Indicator.classList.remove('active');
                    step1Indicator.classList.add('completed');
                }
                const step1Num = document.getElementById('step1-num');
                const step1Icon = document.getElementById('step1-icon');
                if (step1Num) step1Num.style.display = 'none';
                if (step1Icon) step1Icon.style.display = 'inline-block';

                const step2Indicator = document.getElementById('step2-indicator');
                if (step2Indicator) step2Indicator.classList.add('active');

                ProductForm.validation.validateStep2();
            },

            prevStep: function () {
                const step2Content = document.getElementById('step2-content');
                const step1Content = document.getElementById('step1-content');
                if (step2Content) step2Content.style.display = 'none';
                if (step1Content) step1Content.style.display = 'block';

                const step1Indicator = document.getElementById('step1-indicator');
                if (step1Indicator) {
                    step1Indicator.classList.remove('completed');
                    step1Indicator.classList.add('active');
                }
                const step1Icon = document.getElementById('step1-icon');
                const step1Num = document.getElementById('step1-num');
                if (step1Icon) step1Icon.style.display = 'none';
                if (step1Num) step1Num.style.display = 'inline-block';

                const step2Indicator = document.getElementById('step2-indicator');
                if (step2Indicator) step2Indicator.classList.remove('active');

                ProductForm.validation.validateStep1();
            }
        },

        uploader: {
            init: function () {
                const formEl = document.getElementById('digitalProductForm');
                if (!formEl) return;

                formEl.addEventListener('submit', ProductForm.uploader.handleSubmit);
            },

            handleSubmit: function (e) {
                const formEl = document.getElementById('digitalProductForm');
                if (!ProductForm.validation.validateStep1()) {
                    e.preventDefault();
                    ProductForm.stepper.prevStep();
                    return false;
                }
                if (!ProductForm.validation.validateStep2()) {
                    e.preventDefault();
                    return false;
                }

                e.preventDefault();

                const modal = document.getElementById('submitUploadModal');
                const modalFill = document.getElementById('submitModalProgressFill');
                const modalPercent = document.getElementById('submitModalPercentText');
                const modalBytes = document.getElementById('submitModalBytesText');
                const modalDesc = document.getElementById('submitModalDesc');

                if (modal) modal.style.display = 'flex';
                if (modalFill) modalFill.style.width = '0%';
                if (modalPercent) modalPercent.textContent = '0%';
                if (modalBytes) modalBytes.textContent = '0 B / 0 B';

                const btnAdd = document.getElementById('btnAddProduct');
                if (btnAdd) {
                    ProductForm.state.originalSubmitBtnHtml = btnAdd.innerHTML;
                    btnAdd.disabled = true;
                    btnAdd.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                }

                const formData = new FormData(formEl);
                const xhr = new XMLHttpRequest();
                xhr.open(formEl.method, formEl.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
                }

                xhr.upload.addEventListener('progress', function (event) {
                    if (event.lengthComputable) {
                        const percent = Math.min(100, Math.round((event.loaded / event.total) * 100));
                        if (modalFill) modalFill.style.width = percent + '%';
                        if (modalPercent) modalPercent.textContent = percent + '%';
                        if (modalBytes) {
                            modalBytes.textContent = ProductForm.helpers.formatBytes(event.loaded) + ' / ' +
                                ProductForm.helpers.formatBytes(event.total);
                        }
                        if (percent === 100 && modalDesc) {
                            modalDesc.textContent = 'Sedang memproses dan menyimpan produk di server...';
                        }
                    }
                });

                xhr.addEventListener('load', function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        if (modalFill) modalFill.style.width = '100%';
                        if (modalPercent) modalPercent.textContent = '100%';
                        let redirectUrl = ProductForm.config.indexUrl;
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.redirect) redirectUrl = response.redirect;
                        } catch (err) {}
                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 300);
                    } else {
                        if (modal) modal.style.display = 'none';
                        if (btnAdd) {
                            btnAdd.disabled = false;
                            btnAdd.innerHTML = ProductForm.state.originalSubmitBtnHtml || 'Simpan Produk';
                        }
                        let errorMsg = 'Terjadi kesalahan saat menyimpan produk. Silakan coba lagi.';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) errorMsg = response.message;
                            if (response.errors) {
                                errorMsg = Object.values(response.errors).flat().join('\n');
                            }
                        } catch (err) {}
                        alert(errorMsg);
                    }
                });

                xhr.addEventListener('error', function () {
                    if (modal) modal.style.display = 'none';
                    if (btnAdd) {
                        btnAdd.disabled = false;
                        btnAdd.innerHTML = ProductForm.state.originalSubmitBtnHtml || 'Simpan Produk';
                    }
                    alert('Gagal menghubungi server. Periksa koneksi internet Anda.');
                });

                xhr.send(formData);
            }
        },

        init: function () {
            // Read JSON bridge configuration
            const dataScript = document.getElementById('product-form-data');
            if (dataScript) {
                try {
                    const data = JSON.parse(dataScript.textContent || '{}');
                    ProductForm.config = Object.assign(ProductForm.config, data);
                } catch (e) {
                    console.error('Failed to parse product form data:', e);
                }
            }

            ProductForm.price.init();
            ProductForm.photos.init();
            ProductForm.editor.init();
            ProductForm.platform.init();
            ProductForm.validation.init();
            ProductForm.uploader.init();

            ProductForm.validation.validateStep1();
            ProductForm.validation.validateStep2();
        }
    };

    // Expose helpers & methods required by inline HTML onclick or validation guards
    window.nextStep = ProductForm.stepper.nextStep;
    window.prevStep = ProductForm.stepper.prevStep;
    window.removePlatformFile = ProductForm.platform.removeFile;
    window.removeExistingPhoto = ProductForm.photos.removeExistingPhoto;
    window.removeStagedPhoto = ProductForm.photos.removeStagedPhoto;
    window.validateStep1 = ProductForm.validation.validateStep1;
    window.validateStep2 = ProductForm.validation.validateStep2;
    window._getProductPhotosCount = ProductForm.photos.getCount;
    window._isQuillDescFilled = ProductForm.editor.isFilled;

    // Make ProductForm accessible globally if needed for debugging
    window.ProductForm = ProductForm;

    // Auto-init on DOMContentLoaded and Turbo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', ProductForm.init);
    } else {
        ProductForm.init();
    }
    document.addEventListener('turbo:load', ProductForm.init);
})();
