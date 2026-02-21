/**
 * ProgMaker Admin Dashboard - Forms Module
 * Handles advanced form elements like Select2, Summernote, Flatpickr, etc.
 */

$(document).ready(function() {
    'use strict';

    /**
     * Initialize Select2
     */
    function initSelect2() {
        // Basic select with search
        $('.select2-search').select2({
            theme: 'bootstrap-5',
            dir: 'rtl',
            placeholder: 'اختر...',
            allowClear: true,
            language: {
                noResults: function() {
                    return 'لا توجد نتائج';
                },
                searching: function() {
                    return 'جاري البحث...';
                }
            }
        });

        // Multiple select
        $('.select2-multiple').select2({
            theme: 'bootstrap-5',
            dir: 'rtl',
            placeholder: 'اختر خيارات...',
            allowClear: true,
            closeOnSelect: false,
            language: {
                noResults: function() {
                    return 'لا توجد نتائج';
                }
            }
        });

        // Tags mode
        $('.select2-tags').select2({
            theme: 'bootstrap-5',
            dir: 'rtl',
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: 'أضف وسوم...'
        });
    }

    /**
     * Initialize Summernote (Rich Text Editor)
     */
    function initSummernote() {
        const richEditor = $('#richEditor');
        if (richEditor.length) {
            richEditor.summernote({
                height: 250,
                dir: 'rtl',
                lang: 'ar-AR',
                placeholder: 'اكتب محتواك هنا...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                popover: {
                    image: [
                        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    table: [
                        ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                        ['delete', ['deleteRow', 'deleteCol', 'deleteTable']]
                    ]
                },
                callbacks: {
                    onImageUpload: function(files) {
                        // Handle image upload
                        for (let i = 0; i < files.length; i++) {
                            uploadImage(files[i], this);
                        }
                    }
                }
            });
        }

        // Product description editor
        const productDescription = $('#productDescription');
        if (productDescription.length) {
            productDescription.summernote({
                height: 200,
                dir: 'rtl',
                placeholder: 'وصف المنتج...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']]
                ]
            });
        }
    }

    /**
     * Upload image to server (placeholder)
     */
    function uploadImage(file, editor) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const image = $('<img>').attr('src', e.target.result);
            $(editor).summernote('insertNode', image[0]);
        };
        reader.readAsDataURL(file);
    }

    /**
     * Initialize Flatpickr (Date/Time Picker)
     */
    function initFlatpickr() {
        // DateTime picker
        $('.datetime-picker').flatpickr({
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            locale: 'ar',
            theme: document.body.getAttribute('data-theme') === 'dark' ? 'dark' : 'light'
        });

        // Date picker
        $('.date-picker').flatpickr({
            dateFormat: 'Y-m-d',
            locale: 'ar'
        });

        // Time picker
        $('.time-picker').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            locale: 'ar'
        });

        // Range picker
        $('.range-picker').flatpickr({
            mode: 'range',
            dateFormat: 'Y-m-d',
            locale: 'ar'
        });
    }

    /**
     * Initialize password toggle
     */
    function initPasswordToggle() {
        $('.toggle-password').on('click', function() {
            const input = $(this).siblings('input');
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
    }

    /**
     * Initialize file upload
     */
    function initFileUpload() {
        const fileUploads = document.querySelectorAll('.file-upload');
        
        fileUploads.forEach(upload => {
            const input = upload.querySelector('input[type="file"]');
            const label = upload.querySelector('.file-upload-label');
            const fileList = upload.querySelector('.file-list');

            if (!input || !label) return;

            // Drag and drop
            upload.addEventListener('dragover', (e) => {
                e.preventDefault();
                upload.classList.add('dragover');
            });

            upload.addEventListener('dragleave', () => {
                upload.classList.remove('dragover');
            });

            upload.addEventListener('drop', (e) => {
                e.preventDefault();
                upload.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                handleFiles(files, fileList);
            });

            // File input change
            input.addEventListener('change', () => {
                handleFiles(input.files, fileList);
            });
        });

        function handleFiles(files, fileList) {
            if (!fileList) return;

            Array.from(files).forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <i class="bi bi-file-earmark"></i>
                    <span>${file.name}</span>
                    <button type="button" class="remove-file">
                        <i class="bi bi-x"></i>
                    </button>
                `;

                fileItem.querySelector('.remove-file').addEventListener('click', () => {
                    fileItem.remove();
                });

                fileList.appendChild(fileItem);
            });
        }
    }

    /**
     * Initialize range slider
     */
    function initRangeSlider() {
        const rangeInputs = document.querySelectorAll('.form-range');
        
        rangeInputs.forEach(input => {
            const display = input.parentElement.querySelector('.current-value');
            if (display) {
                input.addEventListener('input', () => {
                    display.textContent = input.value;
                });
            }
        });
    }

    /**
     * Initialize form validation
     */
    function initFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            });
        });
    }

    /**
     * Initialize tags input
     */
    function initTagsInput() {
        const tagsInputs = document.querySelectorAll('.tags-input');
        
        tagsInputs.forEach(container => {
            const input = container.querySelector('input');
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const value = input.value.trim();
                    
                    if (value) {
                        addTag(container, value);
                        input.value = '';
                    }
                }
            });
        });

        function addTag(container, value) {
            const tag = document.createElement('span');
            tag.className = 'tag';
            tag.innerHTML = `
                ${value}
                <button type="button" class="remove-tag">
                    <i class="bi bi-x"></i>
                </button>
            `;

            tag.querySelector('.remove-tag').addEventListener('click', () => {
                tag.remove();
            });

            container.insertBefore(tag, container.querySelector('input'));
        }
    }

    /**
     * Initialize modals
     */
    function initModals() {
        // Add User Form
        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'جاري الحفظ...',
                text: 'يرجى الانتظار',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                $('#addUserModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'تم!',
                    text: 'تم إضافة المستخدم بنجاح',
                    confirmButtonColor: '#6366f1'
                });
                this.reset();
            }, 1500);
        });

        // Add Product Form
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'جاري الحفظ...',
                text: 'يرجى الانتظار',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                $('#addProductModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'تم!',
                    text: 'تم إضافة المنتج بنجاح',
                    confirmButtonColor: '#6366f1'
                });
                this.reset();
            }, 1500);
        });
    }

    /**
     * Initialize image preview
     */
    function initImagePreview() {
        const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
        
        imageInputs.forEach(input => {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'image-upload-preview';
            input.parentElement.appendChild(previewContainer);

            input.addEventListener('change', () => {
                previewContainer.innerHTML = '';
                
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const preview = document.createElement('div');
                        preview.className = 'image-preview-item';
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="${file.name}">
                            <button type="button" class="remove-image">
                                <i class="bi bi-x"></i>
                            </button>
                        `;
                        
                        preview.querySelector('.remove-image').addEventListener('click', () => {
                            preview.remove();
                        });
                        
                        previewContainer.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    }

    // Initialize all components
    initSelect2();
    initSummernote();
    initFlatpickr();
    initPasswordToggle();
    initFileUpload();
    initRangeSlider();
    initFormValidation();
    initTagsInput();
    initModals();
    initImagePreview();

    // Re-initialize on page show
    window.addEventListener('pageShow', function() {
        initSelect2();
        initSummernote();
        initFlatpickr();
    });
});
