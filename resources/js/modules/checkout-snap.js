/**
 * checkout-snap.js
 * Modular Midtrans Snap checkout popup integration and response lifecycle handler.
 */

export class CheckoutSnapHandler {
    constructor(options = {}) {
        this.formSelector = options.formSelector || '#checkoutForm';
        this.snapToken = options.snapToken || this.resolveSnapToken();
        this.successUrl = options.successUrl || this.resolveSuccessUrl();
        this.storeUrl = options.storeUrl || this.resolveStoreUrl();

        if (options.autoBind !== false) {
            this.bindEvents();
        }
    }

    resolveSnapToken() {
        const input = document.querySelector('[data-snap-token]');
        return input ? input.dataset.snapToken : (window.snapToken || null);
    }

    resolveSuccessUrl() {
        const input = document.querySelector('[data-success-url]');
        return input ? input.dataset.successUrl : (window.successUrl || null);
    }

    resolveStoreUrl() {
        const input = document.querySelector('[data-store-url]');
        return input ? input.dataset.storeUrl : (window.storeUrl || null);
    }

    bindEvents() {
        const form = document.querySelector(this.formSelector);
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.processCheckout(form);
        });
    }

    async processCheckout(form) {
        if (!window.snap) {
            console.error('Midtrans Snap SDK not loaded');
            alert('Sistem pembayaran belum siap. Silahkan muat ulang halaman.');
            return;
        }

        const formData = new FormData(form);
        const transactionData = Object.fromEntries(formData.entries());
        const storeUrl = form.action || this.storeUrl;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(transactionData)
            });

            if (!response.ok) {
                let errorData = {};
                try {
                    errorData = await response.json();
                } catch (_) {}
                let errorMessage = errorData.message || 'Gagal menyimpan transaksi. Pastikan data valid.';
                if (response.status === 422 && errorData.errors) {
                    const firstKey = Object.keys(errorData.errors)[0];
                    errorMessage = errorData.errors[firstKey][0];
                }
                throw new Error(errorMessage);
            }

            const data = await response.json();
            const snapToken = data.snap_token || this.snapToken;

            this.openSnapModal(snapToken, data.order_id);
        } catch (error) {
            console.error('Checkout error:', error);
            if (window.Swal) {
                window.Swal.fire('Error', error.message, 'error');
            } else {
                alert(error.message);
            }
        }
    }

    openSnapModal(token, orderId) {
        if (!token) {
            console.error('Missing snap token');
            return;
        }

        window.snap.pay(token, {
            onSuccess: (result) => {
                if (window.Swal) {
                    window.Swal.fire({
                        title: 'Memproses...',
                        text: 'Mengarahkan Anda ke status pesanan...',
                        allowOutsideClick: false,
                        didOpen: () => window.Swal.showLoading()
                    });
                }
                const baseSuccessUrl = this.successUrl || window.location.origin;
                const sep = baseSuccessUrl.includes('?') ? '&' : '?';
                window.location.href = `${baseSuccessUrl}${sep}order_id=${orderId || ''}`;
            },
            onPending: (result) => {
                if (window.Swal) {
                    window.Swal.fire({
                        title: 'Menunggu Pembayaran',
                        text: 'Silahkan selesaikan pembayaran Anda sesuai petunjuk yang diberikan.',
                        icon: 'info',
                        confirmButtonText: 'Tutup'
                    });
                }
            },
            onError: (result) => {
                if (window.Swal) {
                    window.Swal.fire('Gagal', 'Terjadi kesalahan dalam pembayaran.', 'error');
                } else {
                    alert('Terjadi kesalahan dalam pembayaran.');
                }
            },
            onClose: () => {
                if (window.Swal) {
                    window.Swal.fire({
                        title: 'Perhatian',
                        text: 'Kamu belum menyelesaikan pembayaran.',
                        icon: 'warning',
                        confirmButtonText: 'Tutup'
                    });
                }
            }
        });
    }
}

export function initCheckoutSnap(options = {}) {
    return new CheckoutSnapHandler(options);
}
