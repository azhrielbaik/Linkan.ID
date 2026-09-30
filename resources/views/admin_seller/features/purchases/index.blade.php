@extends("admin_seller.layouts.app")

@section("page_title", __('admin.my_purchases_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/mypurchase.css') }}">
@endpush

@section("content")
<div class="dashboard-mypurchase-page">


        <!-- Card My Linkan URL -->
        <div class="my-linkan-header">
            <div class="my-linkan-url">
                <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" style="color: #5A5BF1; text-decoration: none;">
                    {{ url('/linkan.id/' . Auth::user()->username) }}
                </a>
            </div>
            <button class="share-button" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')" title="Share">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
        <!-- Card Filter, Sort, Search, and Content -->
        <div class="purchases-card-container" style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;">
            <div class="filter-sort-bar">
                <button class="filter-sort-btn"><i class="fas fa-filter"></i> {{ __('admin.filter') }}</button>
                <button class="filter-sort-btn"><i class="fas fa-sort"></i> {{ __('admin.sorting') }}</button>
                <div class="search-bar">
                    <input type="text" placeholder="{{ __('admin.search_placeholder') }}">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div style="margin-bottom: 14px; font-weight: 500; color: #888;">{{ __('admin.content_purchase_search_result') }}</div>
            <div class="purchases-grid">
                @forelse($purchasedProducts as $product)
                    @if($product)
                    <div class="purchase-card">
                        <img src="{{ resolveProductImageUrl($product->image) }}" alt="Product Image" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 15px;">
                        <div>
                            <div style="font-weight: 600; font-size: 14px;">{{ $product->title }}</div>
                            <div style="font-size: 12px; color: #888; margin-top: 2px;">
                                {{ optional($purchases->firstWhere('product_id', $product->id))->created_at ? optional($purchases->firstWhere('product_id', $product->id))->created_at->format('d M Y') : '-' }}
                            </div>
                            <div style="margin-top: 4px;">
                                <span class="purchase-badge">{{ __('admin.purchased') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                @empty
                <div style="grid-column: 1 / -1; padding: 30px; text-align: center; color: #aaa;">
                    {{ __('admin.no_purchased_content') }}
                </div>
                @endforelse
            </div>
        </div>

</div>
@endsection

@push("scripts")
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copied to clipboard!');
    }, function(err) {
        alert('Failed to copy link');
    });
}
</script>
@endpush
