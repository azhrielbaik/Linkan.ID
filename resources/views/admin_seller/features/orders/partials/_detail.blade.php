<div id="panelOrderTitleHidden" style="display:none;">Order #{{ $order->id }}</div>

<div class="od-summary-card">
    <div class="od-summary-row">
        <span class="od-label">Status</span>
        @php
            $statusLower = strtolower($order->status_label);
            $statusClass = match($statusLower) {
                'completed' => 'od-status-completed',
                'cancelled' => 'od-status-cancelled',
                default     => 'od-status-pending',
            };
        @endphp
        <span class="od-status-badge {{ $statusClass }}">
            {{ $order->status_label }}
        </span>
    </div>
    <div class="od-summary-row">
        <span class="od-label">Date</span>
        <span class="od-value">{{ $order->created_at->format('d/m/Y, H:i:s') }}</span>
    </div>
    @if(!empty($order->order_id))
    <div class="od-summary-row">
        <span class="od-label">Transaction ID</span>
        <span class="od-value" style="font-size: 12.5px; font-family: monospace;">{{ $order->order_id }}</span>
    </div>
    @endif
    @if(!empty($order->formatted_payment_method) && $order->formatted_payment_method !== '-')
    <div class="od-summary-row">
        <span class="od-label">Payment Method</span>
        <span class="od-value">{{ $order->formatted_payment_method }}</span>
    </div>
    @endif
    <div class="od-summary-row">
        <span class="od-label">Total</span>
        <span class="od-total-price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>
</div>

<h4 class="od-section-title">Product Information</h4>
<div class="od-info-card">
    <div class="od-product-title">{{ $order->product ? $order->product->title : 'Digital Product' }}</div>
    @if(!empty($order->qty) && $order->qty > 1)
        <div class="od-buyer-info-subtitle" style="margin-top: 4px;">Jumlah: {{ $order->qty }} item</div>
    @endif
</div>

<h4 class="od-section-title">Buyer Information</h4>
<div class="od-info-card">
    <div class="od-buyer-row">
        <div class="od-avatar">
            {{ $order->buyer_name ? strtoupper(substr($order->buyer_name, 0, 1)) : '?' }}
        </div>
        <div>
            <div class="od-buyer-info-title">{{ $order->buyer_name ?? '-' }}</div>
            <div class="od-buyer-info-subtitle">Buyer Name</div>
        </div>
    </div>
    <div class="od-buyer-row">
        <div class="od-icon-box">
            <i class="far fa-envelope"></i>
        </div>
        <div>
            <div class="od-buyer-info-title">{{ $order->buyer_email ?? '-' }}</div>
            <div class="od-buyer-info-subtitle">Email Address</div>
        </div>
    </div>
</div>
