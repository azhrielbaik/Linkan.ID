<!-- Analytics Cards -->
<div class="stat-cards-grid">
    <div class="stat-card-item">
        <div class="stat-icon purple">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-content">
            <div class="stat-val">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
            <div class="stat-lbl">{{ __('dashboard.total_earnings') }}</div>
        </div>
    </div>
    <div class="stat-card-item">
        <div class="stat-icon pink">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-content">
            <div class="stat-val">{{ number_format($lifetimeOrders) }}</div>
            <div class="stat-lbl">{{ __('dashboard.total_sales') }}</div>
        </div>
    </div>
    <div class="stat-card-item">
        <div class="stat-icon blue">
            <i class="fas fa-box-open"></i>
        </div>
        <div class="stat-content">
            <div class="stat-val">{{ number_format($totalProducts) }}</div>
            <div class="stat-lbl">{{ __('dashboard.active_digital_products') }}</div>
        </div>
    </div>
</div>
