<div id="panelOrderTitleHidden" style="display:none;">{{ __('admin.order') }} #{{ $order->id }}</div>

<div class="od-summary-card">
    <div class="od-summary-row">
        <span class="od-label">{{ __('admin.order_col_status') }}</span>
        @php
            $statusVal = strtolower($order->status_value);
            $statusClass = match($statusVal) {
                'success', 'completed', 'complete' => 'od-status-completed',
                'failed', 'cancelled', 'cancel' => 'od-status-cancelled',
                default => 'od-status-pending',
            };
        @endphp
        <span class="od-status-badge {{ $statusClass }}">
            {{ $order->status_label }}
        </span>
    </div>
    <div class="od-summary-row">
        <span class="od-label">{{ __('admin.order_col_date') }}</span>
        <span class="od-value">{{ $order->created_at ? $order->created_at->translatedFormat('d M Y, H:i') : '-' }}</span>
    </div>
    @if(!empty($order->order_id))
    <div class="od-summary-row">
        <span class="od-label">{{ __('admin.transaction_id') }}</span>
        <span class="od-value" style="font-size: 12.5px; font-family: monospace;">{{ $order->order_id }}</span>
    </div>
    @endif
    @if(!empty($order->formatted_payment_method) && $order->formatted_payment_method !== '-')
    <div class="od-summary-row">
        <span class="od-label">{{ __('admin.payment_method') }}</span>
        <span class="od-value">{{ $order->formatted_payment_method }}</span>
    </div>
    @endif
    <div class="od-summary-row">
        <span class="od-label">{{ __('admin.order_col_total') }}</span>
        <span class="od-total-price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>
</div>

<h4 class="od-section-title">{{ __('admin.product_information') }}</h4>
<div class="od-info-card">
    <div class="od-product-title">{{ $order->product ? $order->product->title : __('admin.digital_product') }}</div>
    @if(!empty($order->qty) && $order->qty > 1)
        <div class="od-buyer-info-subtitle" style="margin-top: 4px;">{{ __('admin.quantity') }}: {{ $order->qty }} {{ __('admin.items') }}</div>
    @endif
</div>

<h4 class="od-section-title">{{ __('admin.buyer_information') }}</h4>
<div class="od-info-card">
    <div class="od-buyer-row">
        <div class="od-avatar">
            {{ $order->buyer_name ? strtoupper(substr($order->buyer_name, 0, 1)) : '?' }}
        </div>
        <div>
            <div class="od-buyer-info-title">{{ $order->buyer_name ?? '-' }}</div>
            <div class="od-buyer-info-subtitle">{{ __('admin.buyer_name') }}</div>
        </div>
    </div>
    <div class="od-buyer-row">
        <div class="od-icon-box">
            <i class="far fa-envelope"></i>
        </div>
        <div>
            <div class="od-buyer-info-title">{{ $order->buyer_email ?? '-' }}</div>
            <div class="od-buyer-info-subtitle">{{ __('admin.email_address') }}</div>
        </div>
    </div>
</div>
