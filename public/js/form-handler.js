document.addEventListener('DOMContentLoaded', function () {
    // 1. Loading States (Prevent double submit & show spinner)
    // ONLY for simple forms NOT managed by Alpine.js
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            // Skip forms managed by Alpine.js (they have their own submit handler)
            // Detected by: form or parent having x-data, or form having @submit / x-on:submit
            const isAlpineManaged = form.closest('[x-data]') || 
                                     form.hasAttribute('x-data') ||
                                     form.hasAttribute('@submit') ||
                                     form.hasAttribute('x-on:submit');
            if (isAlpineManaged) {
                return; // Let Alpine.js handle it
            }

            // Jika form memiliki data-confirm, jangan langsung disable (ditangani oleh confirm alert)
            if (e.target.hasAttribute('data-confirm')) {
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.hasAttribute('data-no-loading')) {
                // Jangan disable jika form invalid secara HTML5
                if (!form.checkValidity()) {
                    return;
                }

                // Hindari disable ganda
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                submitBtn.disabled = true;
                
                // Simpan teks asli
                if (!submitBtn.dataset.originalText) {
                    submitBtn.dataset.originalText = submitBtn.innerHTML;
                }

                // Ganti dengan spinner
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                
                // Tambahkan class opacity
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });
    });

    // Restore button states on pageshow (misal back button browser di-klik)
    window.addEventListener('pageshow', function (e) {
        forms.forEach(form => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.disabled && submitBtn.dataset.originalText) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalText;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
    });

    // 2. Confirmation Dialog Intercept (SweetAlert2)
    // Bisa dipasang di form atau di button
    document.body.addEventListener('click', function (e) {
        // Cari elemen terdekat yang memiliki data-confirm
        const target = e.target.closest('[data-confirm]');
        
        if (target) {
            e.preventDefault();
            
            const message = target.getAttribute('data-confirm');
            const form = target.closest('form');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626', // Red-600
                    cancelButtonColor: '#6b7280', // Gray-500
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (form) {
                            form.submit();
                        } else if (target.tagName === 'A') {
                            window.location.href = target.href;
                        }
                    }
                });
            } else {
                // Fallback JS confirm native
                if (confirm(message)) {
                    if (form) {
                        form.submit();
                    } else if (target.tagName === 'A') {
                        window.location.href = target.href;
                    }
                }
            }
        }
    });
});
