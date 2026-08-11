@extends("layouts.admin")

@section("page_title", "My Purchases")

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/mypurchase.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-mypurchase-page">


        <!-- Card My Linkan URL -->
        <div class="my-linkan-header">
            <div class="my-linkan-url">
                <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" style="color: #FF9040; text-decoration: none;">
                    {{ url('/linkan.id/' . Auth::user()->username) }}
                </a>
            </div>
            <button class="share-button" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')" title="Share">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
        <!-- Card Filter, Sort, Search, and Content -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="filter-sort-bar">
                <button class="filter-sort-btn"><i class="fas fa-filter"></i> Filter</button>
                <button class="filter-sort-btn"><i class="fas fa-sort"></i> Sorting</button>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div style="margin-bottom: 10px; font-weight: 500; color: #888;">Content Purchase Search Result</div>
            <div class="row">
                @forelse($purchasedProducts as $product)
                    @if($product)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div style="display: flex; align-items: center; background: #f7f8fa; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 12px 15px;">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/default-product.png') }}" alt="Product Image" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 15px;" onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}';">
                            <div>
                                <div style="font-weight: 600; color: #222;">{{ $product->title }}</div>
                                <div style="font-size: 12px; color: #888;">
                                    {{ optional($purchases->firstWhere('product_id', $product->id))->created_at ? optional($purchases->firstWhere('product_id', $product->id))->created_at->format('d M Y') : '-' }}
                                </div>
                                <span class="badge bg-secondary" style="font-size: 11px;">Purchased</span>
                            </div>
                        </div>
                    </div>
                    @endif
                @empty
                <div class="col-12">
                    <div style="padding: 30px; text-align: center; color: #aaa;">No purchased content found.</div>
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
