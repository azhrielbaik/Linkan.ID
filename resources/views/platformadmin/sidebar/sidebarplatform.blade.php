{{-- Core platform stylesheets and csrf-token meta are loaded properly in the layout <head> --}}

<script>
    if (localStorage.getItem('sidebar-mini') === 'true') {
        document.body.classList.add('mini-sidebar');
    }
    (function() {
        localStorage.removeItem('platform_theme_mode');
        localStorage.removeItem('platform_theme_color');
        document.documentElement.setAttribute('data-theme', 'light');
        document.documentElement.classList.remove('dark-mode');
        document.documentElement.setAttribute('data-theme-color', '#ed842c');
    })();
</script>

<div class="sidebar" id="sidebar">
    <div class="sidebar-minimize-btn" onclick="toggleMinimize()">
        <i class="fas fa-caret-down"></i>
    </div>

    <div class="sidebar-inner-scroll">
        <div class="logo-container">
            <a href="{{ route('platform-admin.dashboard') }}" class="sidebar-logo-link" title="Linkan.ID Platform Admin">
                <img src="{{ asset('images/Logo.svg') }}" alt="Logo Linkan.ID" class="logo">
                <img src="{{ asset('images/Logo-mini.png') }}" alt="Logomark Linkan.ID" class="logo-mini">
            </a>
            <button type="button" class="sidebar-close" onclick="toggleSidebar()" aria-label="Tutup Menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="menu-label">{{ __('sidebar.main_menu') }}</div>

        <div class="sidebar-nav">
            <a href="{{ route('platform-admin.dashboard') }}" class="{{ request()->routeIs('platform-admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span class="nav-text">{{ __('sidebar.dashboard') }}</span>
            </a>

            <a href="{{ route('platform-admin.users') }}" class="{{ request()->routeIs('platform-admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span class="nav-text">{{ __('sidebar.user_management') }}</span>
            </a>

            <a href="{{ route('platform-admin.products.index') }}" class="{{ request()->routeIs('platform-admin.products*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i><span class="nav-text">{{ __('sidebar.product_management') }}</span>
            </a>

            <a href="{{ route('platform-admin.verifikasi') }}" class="{{ request()->routeIs('platform-admin.verifikasi*') ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i><span class="nav-text">{{ __('sidebar.verification') }}</span>
            </a>

            <a href="{{ route('platform-admin.payouts.index') }}" class="{{ request()->routeIs('platform-admin.payouts*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i><span class="nav-text">{{ __('sidebar.payout_management') }}</span>
            </a>

            <a href="{{ route('platform-admin.logs.activity') }}" class="{{ request()->routeIs('platform-admin.logs.activity*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i><span class="nav-text">{{ __('sidebar.activity_logs') }}</span>
            </a>

            <a href="{{ route('platform-admin.logs.transactions') }}" class="{{ request()->routeIs('platform-admin.logs.transactions*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i><span class="nav-text">{{ __('sidebar.transaction_logs') }}</span>
            </a>

            <a href="{{ route('platform-admin.tickets.index') }}" class="{{ request()->routeIs('platform-admin.tickets*') ? 'active' : '' }}">
                <i class="fas fa-headset"></i><span class="nav-text">{{ __('platform.pusat_bantuan') }}</span>
                @php
                    $pendingTicketsCount = \App\Services\PlatformAdminService::getPendingTicketsCount();
                @endphp
                @if($pendingTicketsCount > 0)
                    <span style="background: #ef4444; color: #fff; font-size: 11px; font-weight: 800; padding: 2px 7px; border-radius: 10px; margin-left: auto;">
                        {{ $pendingTicketsCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('platform-admin.settings.index') }}" class="{{ request()->routeIs('platform-admin.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i><span class="nav-text">{{ __('sidebar.platform_settings') }}</span>
            </a>
        </div>

        <hr>

        <div class="marketing-tools">
            <form action="{{ route('logout') }}" method="POST" style="display: flex; align-items: center;" id="platformLogoutForm" onsubmit="if(window.platformEventSource) window.platformEventSource.close();">
                @csrf
                <button type="button" onclick="confirmPlatformLogout()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">{{ __('sidebar.logout') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

{{-- SweetAlert2 CDN & Linkan Integration --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function toggleSidebar() {
        if (window.innerWidth > 900) {
            toggleMinimize();
            return;
        }
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (sidebar) {
            sidebar.classList.toggle('show');
            if (backdrop) {
                backdrop.classList.toggle('show');
            }
        }
    }

    function toggleMinimize() {
        document.body.classList.toggle('mini-sidebar');
        const isMini = document.body.classList.contains('mini-sidebar');
        localStorage.setItem('sidebar-mini', isMini ? 'true' : 'false');
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth > 900) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
            if (backdrop && backdrop.classList.contains('show')) {
                backdrop.classList.remove('show');
            }
        }
    });

    // Global Modal Konfirmasi Linkan.ID
    window.showConfirmModal = function(options) {
        const isDanger = options.confirmDanger || false;
        const confirmBtnClass = isDanger ? 'linkan-swal-confirm-danger-btn' : 'linkan-swal-confirm-btn';

        // Tentukan icon HTML dan class warna pendukung
        let iconType = options.icon || (isDanger ? 'warning' : 'question');
        let iconHtml = options.iconHtml || '';
        if (!iconHtml) {
            if (isDanger || iconType === 'warning' || iconType === 'error') {
                iconHtml = '<i class="fas fa-exclamation-triangle"></i>';
            } else if (iconType === 'success') {
                iconHtml = '<i class="fas fa-check"></i>';
            } else {
                iconHtml = '<i class="fas fa-exclamation-triangle"></i>';
            }
        }

        Swal.fire({
            title: options.title || 'Konfirmasi Aksi',
            text: options.text || 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            icon: iconType,
            iconHtml: iconHtml,
            showCancelButton: true,
            confirmButtonText: options.confirmText || (isDanger ? 'Ya, Lanjutkan' : 'Ya, Setujui'),
            cancelButtonText: options.cancelText || 'Batal',
            customClass: {
                popup: 'linkan-swal-popup',
                title: 'linkan-swal-title',
                htmlContainer: 'linkan-swal-html',
                confirmButton: confirmBtnClass,
                cancelButton: 'linkan-swal-cancel-btn',
                actions: 'linkan-swal-actions'
            },
            buttonsStyling: false,
            reverseButtons: true,
            showClass: {
                popup: 'linkan-swal-show'
            },
            hideClass: {
                popup: 'linkan-swal-hide'
            },
            backdrop: 'rgba(15, 23, 42, 0.65)'
        }).then((result) => {
            if (result.isConfirmed && typeof options.onConfirm === 'function') {
                options.onConfirm();
            }
        });
    };

    function confirmPlatformLogout() {
        showConfirmModal({
            title: 'Keluar dari Sesi Admin?',
            text: 'Anda akan keluar dari panel kontrol Platform Admin Linkan.ID.',
            icon: 'question',
            confirmText: '<i class="fas fa-sign-out-alt"></i> Ya, Logout',
            confirmDanger: true,
            onConfirm: () => {
                setPlatformActionLoading(document.querySelector('#platformLogoutForm button'), 'Keluar...');
                document.getElementById('platformLogoutForm').submit();
            }
        });
    }

    function setPlatformActionLoading(button, loadingText) {
        if (!button || button.classList.contains('platform-action-loading')) return;
        button.classList.add('platform-action-loading');
        button.disabled = true;
        button.dataset.originalContent = button.innerHTML;
        button.innerHTML = `<span class="platform-action-spinner" aria-hidden="true"></span><span class="platform-action-label">${loadingText || 'Memproses...'}</span>`;
        button.setAttribute('aria-busy', 'true');
    }

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || form.id === 'platformLogoutForm') return;
        if ((form.method && form.method.toUpperCase() === 'GET') || form.classList.contains('search-form') || form.classList.contains('filter-form') || form.classList.contains('p-filter-inputs')) return;

        const submitButton = event.submitter || form.querySelector('button[type="submit"]:not([disabled])');
        if (submitButton && !submitButton.classList.contains('gooey-search-btn') && !submitButton.classList.contains('btn-filter')) {
            setPlatformActionLoading(submitButton);
        }
    });
</script>

{{-- Floating Toast Notifications for Platform Admin --}}
@include('platformadmin.partials.toast')

{{-- Dynamic Theme & Dark Mode Script --}}
<script src="{{ asset('js/platform/theme.js') }}"></script>
{{-- Modern Custom Dropdown Engine --}}
<script src="{{ asset('js/platform/custom-dropdown.js') }}"></script>
{{-- Modern Expanding Capsule Tab Navbar Engine --}}
<script src="{{ asset('js/platform/tabs.js') }}"></script>
{{-- Modern Gooey Search Bar & Animated Autocomplete Engine --}}
<script src="{{ asset('js/platform/gooey-search.js') }}"></script>
{{-- Modern Date Range Picker Engine --}}
<script src="{{ asset('js/platform/custom-datepicker.js') }}"></script>

{{-- Modern Table Enhancement Engine --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.table-card table, .p-tickets-table-card table, table').forEach(function(table) {
            let hashIndex = -1;
            const ths = table.querySelectorAll('thead th');
            ths.forEach(function(th, idx) {
                if (th.textContent.trim() === '#') hashIndex = idx;
            });
            if (hashIndex !== -1) {
                table.querySelectorAll('tbody tr').forEach(function(tr) {
                    const td = tr.children[hashIndex];
                    if (td && !td.querySelector('.table-index-badge') && !td.querySelector('input')) {
                        const text = td.textContent.trim();
                        if (text && !isNaN(text)) {
                            td.innerHTML = `<span class="table-index-badge">${text}</span>`;
                        }
                    }
                });
            }
        });
    });
</script>

