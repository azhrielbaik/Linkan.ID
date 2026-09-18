<div id="panelOrderTitleHidden" style="display:none;">Order #{{ $order->id }}</div>
<div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
        <span style="color: #64748b; font-weight: 600; font-size: 13px;">Status</span>
        <span style="font-weight: 800; color: {{ $order->status === 'success' ? '#ED842C' : ($order->status === 'failed' ? '#ef4444' : '#1e293b') }}; text-transform: capitalize; font-size: 13px;">{{ $order->status }}</span>
    </div>
    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
        <span style="color: #64748b; font-weight: 600; font-size: 13px;">Date</span>
        <span style="font-weight: 700; color: #1e293b; font-size: 13px;">{{ $order->created_at->format('d/m/Y, H:i:s') }}</span>
    </div>
    <div style="display: flex; justify-content: space-between;">
        <span style="color: #64748b; font-weight: 600; font-size: 13px;">Total</span>
        <span style="font-weight: 800; color: #ED842C; font-size: 16px;">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>
</div>

<h4 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 15px;">Product Information</h4>
<div style="border: 1px solid #f1f5f9; border-radius: 12px; padding: 15px; margin-bottom: 25px;">
    <div style="font-weight: 700; color: #1e293b;">{{ $order->product ? $order->product->title : 'Digital Product' }}</div>
</div>

<h4 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 15px;">Buyer Information</h4>
<div style="border: 1px solid #f1f5f9; border-radius: 12px; padding: 15px;">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: #ED842C; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">
            {{ $order->buyer_name ? strtoupper(substr($order->buyer_name, 0, 1)) : '?' }}
        </div>
        <div>
            <div style="font-weight: 700; color: #1e293b; margin-bottom: 2px;">{{ $order->buyer_name ?? '-' }}</div>
            <div style="color: #64748b; font-size: 13px;">Buyer Name</div>
        </div>
    </div>
    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 16px;">
            <i class="far fa-envelope"></i>
        </div>
        <div>
            <div style="font-weight: 700; color: #1e293b; margin-bottom: 2px;">{{ $order->buyer_email ?? '-' }}</div>
            <div style="color: #64748b; font-size: 13px;">Email Address</div>
        </div>
    </div>
</div>
