{{-- Seller Notification Bell & Dropdown --}}
<div class="seller-notif-wrapper">
    <button type="button" class="action-icon seller-notif-btn" id="sellerNotifBtn" onclick="toggleSellerNotif(event)" title="Notifikasi" aria-label="Notifikasi">
        <i class="far fa-bell"></i>
        <span class="seller-notif-badge" id="sellerNotifBadge" style="display: {{ ($sellerHasUnread ?? false) ? 'flex' : 'none' }};">{{ $sellerDisplayCount ?? '0' }}</span>
    </button>

    <!-- Notification Dropdown Panel -->
    <div class="seller-notif-dropdown" id="sellerNotifDropdown">
        <div class="seller-notif-header">
            <div class="seller-notif-title">Notifications</div>
            <div class="seller-notif-badge-header">{{ $sellerHeaderCount ?? '00' }} Notifications</div>
        </div>

        <div class="seller-notif-list" id="sellerNotifList">
            @forelse ($sellerNotifications ?? [] as $item)
                @include('admin_seller.layouts.partials._notification-item', ['item' => $item])
            @empty
                <div class="notif-empty-state">
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>

        <div class="seller-notif-footer">
            <a href="#" class="no-loader" onclick="if(typeof markAllSellerNotifsRead === 'function') markAllSellerNotifsRead(event, false, this)">Read All Messages</a>
        </div>
    </div>
</div>
