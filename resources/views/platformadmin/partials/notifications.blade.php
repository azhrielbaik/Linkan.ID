{{-- Platform Admin Notification Component --}}
<div class="header-actions">
    <div class="notif-wrapper">
        <button type="button" class="action-icon notif-bell-btn" id="platformNotifBtn" onclick="togglePlatformNotif(event)" title="Notifikasi Platform" aria-label="Notifikasi Platform">
            <i class="far fa-bell"></i>
            <span class="notif-badge" id="platformNotifBadge" style="display: none;">0</span>
        </button>

        {{-- Dropdown Panel --}}
        <div class="notif-dropdown" id="platformNotifDropdown">
            <div class="notif-dropdown-header">
                <div class="notif-dropdown-title">
                    <i class="fas fa-bell"></i> Notifikasi Seller
                </div>
                <span class="notif-total-pill" id="platformNotifTotal">0 Baru</span>
            </div>

            <div class="notif-filter-tabs">
                <button type="button" class="notif-tab active" onclick="filterPlatformNotif('all', this)">Semua</button>
                <button type="button" class="notif-tab" onclick="filterPlatformNotif('product', this)">Produk</button>
                <button type="button" class="notif-tab" onclick="filterPlatformNotif('payout', this)">Payout</button>
                <button type="button" class="notif-tab" onclick="filterPlatformNotif('appeal', this)">Banding</button>
                <button type="button" class="notif-tab" onclick="filterPlatformNotif('reset_request', this)">Reset PW</button>
            </div>

            <div class="notif-scroll-area" id="platformNotifList">
                <div class="notif-loading">
                    <i class="fas fa-spinner fa-spin"></i> Memuat notifikasi...
                </div>
            </div>

            <div class="notif-dropdown-footer">
                <a href="{{ route('platform-admin.verifikasi') }}">
                    <i class="fas fa-box-open"></i> Verifikasi
                </a>
                <span class="dot">&bull;</span>
                <a href="{{ route('platform-admin.payouts.index') }}">
                    <i class="fas fa-money-bill-wave"></i> Payout
                </a>
                <span class="dot">&bull;</span>
                <a href="{{ route('platform-admin.users', ['view' => 'appeals']) }}">
                    <i class="fas fa-shield-alt"></i> Banding
                </a>
                <span class="dot">&bull;</span>
                <a href="{{ route('platform-admin.users', ['view' => 'reset_requests']) }}">
                    <i class="fas fa-key"></i> Reset PW
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    window.PlatformNotifEndpoint = "{{ route('platform-admin.notifications') }}";
    window.PlatformNotifSSEEndpoint = "{{ route('platform-admin.notifications.stream') }}";
</script>
