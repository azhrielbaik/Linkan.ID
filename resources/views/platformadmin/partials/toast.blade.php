{{-- Linkan.ID Platform Admin Floating Toast Notifications --}}
<div class="toast-container" id="platformToastContainer" aria-live="polite" aria-atomic="true">
    @if (session('success'))
        <div class="toast-item toast-success" role="alert" data-auto-dismiss="4500">
            <div class="toast-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Berhasil</div>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
            <button type="button" class="toast-close" onclick="closeToast(this.closest('.toast-item'))" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress">
                <div class="toast-progress-bar"></div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast-item toast-error" role="alert" data-auto-dismiss="5500">
            <div class="toast-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Terjadi Kesalahan</div>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
            <button type="button" class="toast-close" onclick="closeToast(this.closest('.toast-item'))" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress">
                <div class="toast-progress-bar"></div>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="toast-item toast-warning" role="alert" data-auto-dismiss="5000">
            <div class="toast-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Perhatian</div>
                <div class="toast-message">{{ session('warning') }}</div>
            </div>
            <button type="button" class="toast-close" onclick="closeToast(this.closest('.toast-item'))" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress">
                <div class="toast-progress-bar"></div>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="toast-item toast-info" role="alert" data-auto-dismiss="4500">
            <div class="toast-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Informasi</div>
                <div class="toast-message">{{ session('info') }}</div>
            </div>
            <button type="button" class="toast-close" onclick="closeToast(this.closest('.toast-item'))" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress">
                <div class="toast-progress-bar"></div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="toast-item toast-error" role="alert" data-auto-dismiss="6000">
            <div class="toast-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Periksa Input Anda</div>
                <div class="toast-message">
                    <ul style="margin: 0; padding-left: 16px; font-size: 12px; line-height: 1.5;">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="toast-close" onclick="closeToast(this.closest('.toast-item'))" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress">
                <div class="toast-progress-bar"></div>
            </div>
        </div>
    @endif
</div>

<script>
    (function () {
        // Global Toast Notification Manager
        window.closeToast = function (toastElem) {
            if (!toastElem) return;
            toastElem.classList.add('toast-hiding');
            setTimeout(() => {
                if (toastElem.parentElement) {
                    toastElem.remove();
                }
            }, 300);
        };

        window.showToast = function (message, type = 'success', title = null, duration = 4500) {
            const container = document.getElementById('platformToastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-item toast-${type}`;
            toast.setAttribute('role', 'alert');

            let iconClass = 'fa-check-circle';
            let defaultTitle = 'Berhasil';
            if (type === 'error') {
                iconClass = 'fa-exclamation-circle';
                defaultTitle = 'Terjadi Kesalahan';
            } else if (type === 'warning') {
                iconClass = 'fa-exclamation-triangle';
                defaultTitle = 'Perhatian';
            } else if (type === 'info') {
                iconClass = 'fa-info-circle';
                defaultTitle = 'Informasi';
            }

            const toastTitle = title || defaultTitle;

            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${toastTitle}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button type="button" class="toast-close" onclick="closeToast(this.closest('.toast-item'))" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
                <div class="toast-progress">
                    <div class="toast-progress-bar" style="animation-duration: ${duration}ms;"></div>
                </div>
            `;

            container.appendChild(toast);
            initToastTimer(toast, duration);
        };

        window.LinkanToast = {
            show: window.showToast,
            success: (msg, title, dur) => window.showToast(msg, 'success', title, dur),
            error: (msg, title, dur) => window.showToast(msg, 'error', title, dur),
            warning: (msg, title, dur) => window.showToast(msg, 'warning', title, dur),
            info: (msg, title, dur) => window.showToast(msg, 'info', title, dur)
        };

        function initToastTimer(toast, customDuration) {
            const duration = customDuration || parseInt(toast.getAttribute('data-auto-dismiss')) || 4500;
            const bar = toast.querySelector('.toast-progress-bar');
            if (bar) {
                bar.style.animationDuration = duration + 'ms';
            }

            let timeoutId = setTimeout(() => {
                window.closeToast(toast);
            }, duration);

            toast.addEventListener('mouseenter', () => {
                clearTimeout(timeoutId);
                if (bar) bar.style.animationPlayState = 'paused';
            });

            toast.addEventListener('mouseleave', () => {
                if (bar) bar.style.animationPlayState = 'running';
                timeoutId = setTimeout(() => {
                    window.closeToast(toast);
                }, 1500);
            });
        }

        // Initialize server-rendered toasts on load
        document.addEventListener('DOMContentLoaded', function () {
            const toasts = document.querySelectorAll('.toast-item');
            toasts.forEach(toast => initToastTimer(toast));
        });
    })();
</script>
