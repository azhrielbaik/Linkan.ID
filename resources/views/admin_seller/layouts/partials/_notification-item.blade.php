{{-- Notification Item Partial --}}
@php
    $type = $item['type'] ?? 'default';
    $status = $item['status'] ?? 'default';
    $isSuccess = in_array($status, ['success', 'completed', 'approved']);
    $isDanger = in_array($status, ['failed', 'rejected', 'suspended']);

    $iconMap = [
        'transaction'  => $isSuccess ? 'fas fa-shopping-cart' : 'fas fa-clock',
        'payout'       => $isSuccess ? 'fas fa-wallet' : 'fas fa-times-circle',
        'system_alert' => 'fas fa-exclamation-triangle',
        'appeal'       => 'fas fa-balance-scale',
        'broadcast'    => 'fas fa-bullhorn',
    ];
    $icon = $iconMap[$type] ?? 'fas fa-bell';

    if ($type === 'broadcast') {
        $stateClass = 'state-blue';
    } elseif ($isSuccess) {
        $stateClass = 'state-green';
    } elseif ($isDanger) {
        $stateClass = 'state-red';
    } elseif ($status === 'pending') {
        $stateClass = 'state-orange';
    } else {
        $stateClass = 'state-gray';
    }
@endphp
<a href="{{ $item['url'] ?? '#' }}" class="notif-item no-loader" onclick="if(typeof markSellerNotifRead === 'function') markSellerNotifRead(event, '{{ $item['id'] }}', this)">
    <div class="notif-avatar-box">
        @if(!empty($item['avatar_url']))
            <img src="{{ $item['avatar_url'] }}" alt="Avatar" class="notif-avatar">
        @else
            <div class="notif-sys-icon">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
        <div class="notif-state-badge {{ $stateClass }}">
            <i class="{{ $icon }}"></i>
        </div>
    </div>
    <div class="notif-content">
        <p class="notif-message">{{ $item['message'] }}</p>
        <span class="notif-time">{{ $item['time_ago'] }}</span>
    </div>
</a>
