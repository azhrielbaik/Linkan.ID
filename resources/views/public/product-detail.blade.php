<?php
    if (!function_exists("resolveProductImageUrl")) {
        function resolveProductImageUrl($path) {
            if (empty($path)) return "https://via.placeholder.com/600x600?text=No+Image";
            if (Str::startsWith($path, ["http://", "https://", "data:image/", "/storage/"])) {
                return $path;
            }
            return Storage::url($path);
        }
    }
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    @php
        $pageTitle = $product->title . ' - Detail';
        $pageDesc = strip_tags($product->description ?? 'Beli ' . $product->title . ' di Linkan.id');
        $pageImage = resolveProductImageUrl($product->image);
        $pageUrl = url()->current();
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ Str::limit($pageDesc, 150) }}">
    <link rel="canonical" href="{{ $pageUrl }}">

    <!-- Open Graph -->
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ Str::limit($pageDesc, 150) }}">
    <meta property="og:image" content="{{ $pageImage }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $pageUrl }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ Str::limit($pageDesc, 150) }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $product->title }}",
      "description": "{{ strip_tags($product->description) }}",
      "image": "{{ $pageImage }}",
      "offers": {
        "@type": "Offer",
        "priceCurrency": "IDR",
        "price": "{{ $product->price }}",
        "availability": "https://schema.org/InStock",
        "url": "{{ $pageUrl }}"
      }
    }
    </script>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }
        body {
            background: #ffffff;
            color: #333;
            padding: 40px 20px;
        }

        /* Container */
        /* Breadcrumb Styles */
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #64748b;
        }
        .breadcrumb ol {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .breadcrumb li {
            display: flex;
            align-items: center;
        }
        .breadcrumb li:not(:last-child)::after {
            content: "/"; 
            margin: 0 8px;
            font-size: 12px;
            color: #cbd5e1;
        }
        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.2s;
        }
        .breadcrumb a:hover {
            color: #2563eb;
            text-decoration: underline;
        }
        .breadcrumb li[aria-current="page"] {
            font-weight: 500;
            color: #334155;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Top Section: Left and Right Columns */
        .product-top {
            display: flex;
            gap: 50px;
            margin-bottom: 60px;
        }

        /* Left Column */
        .product-left {
            flex: 1;
            max-width: 550px;
        }
        .main-img-wrap {
            width: 100%;
            height: 550px;
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .main-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .thumbnail-gallery {
            display: flex;
            gap: 12px;
            overflow-x: auto;
        }
        .thumbnail-gallery img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.2s;
        }
        .thumbnail-gallery img:hover, .thumbnail-gallery img.active {
            border-color: #ED842C;
        }

        /* Right Column */
        .product-right {
            flex: 1;
            padding-top: 10px;
        }
        .tags {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .tag-stock {
            background: #10b981;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .tag-category {
            color: #64748b;
            font-size: 14px;
        }
        
        .title {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .short-desc {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .price-wrap {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 20px;
        }
        .price-current {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
        }
        .price-old {
            font-size: 20px;
            color: #94a3b8;
            text-decoration: line-through;
            font-weight: 600;
        }
        
        .rating-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
        }
        .stars {
            color: #fbbf24;
            font-size: 16px;
        }
        .reviews {
            color: #3b82f6;
            font-size: 14px;
        }

        .qty-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 30px;
        }
        .label {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
            width: 70px;
        }
        
        .qty-selector {
            display: flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
        }
        .qty-btn {
            background: #f8fafc;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 18px;
            color: #3b82f6;
            transition: 0.2s;
        }
        .qty-btn:hover {
            background: #e2e8f0;
        }
        .qty-input {
            width: 50px;
            text-align: center;
            border: none;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            font-size: 16px;
            padding: 8px 0;
            outline: none;
        }
        
        .action-buttons {
            display: flex;
            gap: 16px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
        }
        .btn-buy {
            flex: 1;
            background: #6366f1;
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
            text-decoration: none;
        }
        .btn-buy:hover {
            background: #4f46e5;
        }
        .btn-cart {
            flex: 1;
            background: #f87171;
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
            text-decoration: none;
        }
        .btn-cart:hover {
            background: #ef4444;
        }

        .delivery-info {
            font-size: 14px;
            color: #64748b;
        }
        .delivery-link {
            color: #3b82f6;
            text-decoration: none;
            display: block;
            margin-top: 4px;
        }

        /* Bottom Section: Tabs */
        .bottom-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 40px;
        }
        .tabs {
            display: flex;
            gap: 40px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 30px;
        }
        .tab {
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
            padding-bottom: 12px;
            cursor: pointer;
            position: relative;
        }
        .tab.active {
            color: #3b82f6;
        }
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #3b82f6;
        }
        
        .tab-content {
            font-size: 16px;
            color: #475569;
            line-height: 1.8;
            display: none;
        }
        .tab-content.active {
            display: block;
        }

        /* Tab Description Rich Text & List Styling */
        #tab-description {
            font-size: 16px;
            color: #475569;
            line-height: 1.8;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        #tab-description p {
            margin-bottom: 12px;
            line-height: 1.8;
        }
        #tab-description ul,
        #tab-description ol {
            margin-top: 8px;
            margin-bottom: 16px;
            padding-left: 28px;
            list-style-position: outside;
        }
        #tab-description ul {
            list-style-type: disc;
        }
        #tab-description ol {
            list-style-type: decimal;
        }
        #tab-description li {
            margin-bottom: 6px;
            line-height: 1.7;
            padding-left: 4px;
        }
        #tab-description ul ul,
        #tab-description ol ol,
        #tab-description ul ol,
        #tab-description ol ul {
            margin-top: 4px;
            margin-bottom: 4px;
            padding-left: 22px;
        }
        #tab-description img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 12px 0;
        }
        #tab-description table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            display: block;
            overflow-x: auto;
        }
        #tab-description blockquote {
            border-left: 4px solid #ed842c;
            padding-left: 14px;
            margin: 12px 0;
            color: #64748b;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .product-top {
                flex-direction: column;
            }
            .product-left, .product-right {
                max-width: 100%;
            }
            .action-buttons {
                flex-direction: column;
            }
            /* Mobile List & Tab Description: Ensure bullet & numbers are never cut off */
            #tab-description ul,
            #tab-description ol {
                padding-left: 28px !important;
                margin-left: 0 !important;
                margin-bottom: 14px !important;
            }
            #tab-description li {
                padding-left: 4px !important;
                margin-bottom: 6px !important;
            }
            #tab-description ul ul,
            #tab-description ol ol,
            #tab-description ul ol,
            #tab-description ol ul {
                padding-left: 20px !important;
            }
        }

        /* Modal Cart Custom Style */
        #cartModal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        #cartModal .cart-container {
            background: #fff;
            margin: 6% auto;
            padding: 0;
            border-radius: 16px;
            width: 95%;
            max-width: 420px;
            position: relative;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            overflow: hidden;
            font-family: inherit;
        }
        #cartModal .cart-header {
            padding: 20px 24px 10px 24px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        #cartModal .cart-header h3 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
        #cartModal .close-btn {
            background: transparent;
            border: none;
            font-size: 26px;
            color: #888;
            cursor: pointer;
            transition: color 0.2s;
        }
        #cartModal .close-btn:hover {
            color: #ff7a00;
        }
        #cartModal .cart-product {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 18px 24px 10px 24px;
        }
        #cartModal .cart-product img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        #cartModal .cart-product-info {
            flex: 1;
        }
        #cartModal .cart-product-info div {
            margin-bottom: 2px;
        }
        #cartModal .cart-product-title {
            font-size: 15px;
            font-weight: bold;
            color: #333;
        }
        #cartModal .cart-product-qty,
        #cartModal .cart-product-price {
            font-size: 13px;
            color: #555;
        }
        #cartModal .cart-product-edit {
            background: none;
            color: blue;
            border: none;
            cursor: pointer;
            font-size: 13px;
            padding: 0;
            margin-top: 2px;
        }
        #cartModal #editSection {
            display: none;
            padding: 0 24px 10px 24px;
        }
        #cartModal #editSection label {
            font-size: 13px;
            color: #888;
        }
        #cartModal #editSection input[type=number] {
            width: 100%;
            padding: 6px;
            margin: 6px 0 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        #cartModal #editSection .edit-btns {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        #cartModal #editSection button {
            padding: 6px 16px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
        }
        #cartModal #editSection #cancelEdit {
            background: #ccc;
            color: #333;
        }
        #cartModal #editSection #updateQty {
            background: #6366f1;
            color: white;
        }
        #cartModal .order-summary-label {
            margin: 10px 0 0 0;
            color: #888;
            font-size: 12px;
            font-weight: bold;
            padding: 0 24px;
        }
        #cartModal .order-summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            padding: 2px 24px;
        }
        #cartModal .cart-btn {
            margin: 16px 24px 20px 24px;
            width: calc(100% - 48px);
            padding: 12px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            display: block;
            text-decoration: none;
        }
        #cartModal .cart-btn:hover {
            background: #4f46e5;
        }
    </style>
</head>
<body>

@php
    // Prepare images from media_files or fallback to image
    $images = [];
    $mediaFiles = is_string($product->media_files) ? json_decode($product->media_files, true) : $product->media_files;
    if (is_array($mediaFiles) && count($mediaFiles) > 0) {
        foreach ($mediaFiles as $media) {
            if (isset($media['url']) || isset($media['path'])) {
                $images[] = $media['url'] ?? $media['path'];
            }
        }
    }
    if (empty($images) && $product->image) {
        $images[] = $product->image;
    }
    
    $mainImage = count($images) > 0 ? resolveProductImageUrl($images[0]) : 'https://via.placeholder.com/600x600?text=No+Image';
    
    // Simulate sale price if none exists (just for UI demonstration based on design)
    $originalPrice = $product->sale_price ? $product->price : ($product->price * 1.2);
    $currentPrice = $product->sale_price ? $product->sale_price : $product->price;
@endphp

<div class="container">
    <nav class="breadcrumb" aria-label="breadcrumb">
        <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>Digital Products</li>
            <li aria-current="page">{{ $product->title }}</li>
        </ol>
    </nav>
    <div class="product-top">
        <!-- Left Column: Images -->
        <div class="product-left">
            <div class="main-img-wrap">
                <img id="mainDisplayImage" src="{{ $mainImage }}" alt="{{ $product->title }}">
            </div>
            
            <div class="thumbnail-gallery">
                @if(count($images) > 0)
                    @foreach($images as $index => $img)
                        <img src="{{ resolveProductImageUrl($img) }}" alt="Thumbnail" class="{{ $index === 0 ? 'active' : '' }}" onclick="changeImage(this, '{{ resolveProductImageUrl($img) }}')">
                    @endforeach
                @else
                    <img src="{{ $mainImage }}" alt="Thumbnail" class="active">
                @endif
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="product-right">
            <div class="tags">
                <span class="tag-stock">In Stock</span>
                <span class="tag-category">Digital Product</span>
            </div>
            
            <h1 class="title">{{ $product->title }}</h1>
            <p class="short-desc">
                {{ Str::limit(strip_tags($product->description), 100) }}
            </p>
            
            <div class="price-wrap">
                <div class="price-current">Rp {{ number_format($currentPrice, 0, ',', '.') }}</div>
                @if($originalPrice > $currentPrice)
                    <div class="price-old">Rp {{ number_format($originalPrice, 0, ',', '.') }}</div>
                @endif
            </div>
            
            <div class="rating-wrap">
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <div class="reviews">(236 reviews)</div>
            </div>
            
            <div class="qty-wrap">
                <div class="label">QTY:</div>
                <div class="qty-selector">
                    <button type="button" class="qty-btn" onclick="updateMainQty(-1)">-</button>
                    <input type="text" id="mainQtyInput" class="qty-input" value="1" readonly>
                    <button type="button" class="qty-btn" onclick="updateMainQty(1)">+</button>
                </div>
            </div>
            
            <div class="action-buttons">
                <!-- Link to trigger modal via JS -->
                <a href="{{ route('track.click', ['link_id' => $user->username, 'target' => $product->platform_url ?? '#'], false) }}" class="btn-buy" id="btnBuyNow">Buy Now</a>
                <a href="{{ route('track.click', ['link_id' => $user->username, 'target' => $product->platform_url ?? '#'], false) }}" class="btn-cart" id="btnAddToCart">Add to Cart</a>
            </div>
            
            <div class="delivery-info">
                Dispatched in 2-3 weeks
                <a href="#" class="delivery-link">Why the longer time for delivery?</a>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="bottom-section">
        <div class="tabs">
            <div class="tab active" onclick="switchTab('description', this)">Description</div>
            <div class="tab" onclick="switchTab('reviews', this)">Reviews</div>
        </div>
        
        <div id="tab-description" class="tab-content active">
            {!! $product->description !!}
        </div>
        
        <div id="tab-reviews" class="tab-content">
            <p>Customer reviews will appear here.</p>
        </div>
    </div>
</div>

<!-- Modal Popup (Cart Summary) -->
<div id="cartModal">
    <div class="cart-container">
        <div class="cart-header">
            <h3>Cart (<span id="cartCount">1</span>)</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        
        <div class="cart-product">
            <img src="{{ $mainImage }}" alt="{{ $product->title }}">
            <div class="cart-product-info">
                <div class="cart-product-title">{{ $product->title }}</div>
                <div class="cart-product-qty">Qty. <span id="modalQty">1</span></div>
                <div class="cart-product-price">IDR <span id="modalPrice">{{ number_format($currentPrice, 0, ',', '.') }}</span></div>
                <button id="editButton" class="cart-product-edit">Edit</button>
            </div>
        </div>
        
        <div id="editSection">
            <label for="qtyInput">Quantity:</label>
            <input type="number" id="qtyInput" value="1" min="1">
            <div class="edit-btns">
                <button id="cancelEdit">Cancel</button>
                <button id="updateQty">Update</button>
            </div>
        </div>
        
        <div class="order-summary-label">ORDER SUMMARY</div>
        <div class="order-summary-row" style="margin-top: 10px;">
            <div>Total (<span id="cartCount2">1</span> Items)</div>
            <div>IDR <span id="totalItem">{{ number_format($currentPrice, 0, ',', '.') }}</span></div>
        </div>
        <div class="order-summary-row bold" style="margin-top: 10px; margin-bottom: 20px;">
            <div>Grand total</div>
            <div>IDR <span id="grandTotal">{{ number_format($currentPrice, 0, ',', '.') }}</span></div>
        </div>
        
        <a href="{{ route('checkout', ['id' => $product->id]) }}" class="cart-btn" onclick="closeModal()">Checkout</a>
    </div>
</div>

<script>
    const price = {{ $currentPrice }};
    
    // Main page QTY Logic
    let mainQty = 1;
    const mainQtyInput = document.getElementById('mainQtyInput');
    
    function updateMainQty(change) {
        if (mainQty + change >= 1) {
            mainQty += change;
            mainQtyInput.value = mainQty;
            syncModalQty();
        }
    }
    
    // Image Gallery Logic
    function changeImage(el, src) {
        document.getElementById('mainDisplayImage').src = src;
        document.querySelectorAll('.thumbnail-gallery img').forEach(img => img.classList.remove('active'));
        el.classList.add('active');
    }
    
    // Tabs Logic
    function switchTab(tabId, el) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('tab-' + tabId).classList.add('active');
    }

    // Modal Logic
    const cartModal = document.getElementById('cartModal');
    const editButton = document.getElementById('editButton');
    const editSection = document.getElementById('editSection');
    const cancelEdit = document.getElementById('cancelEdit');
    const updateQtyBtn = document.getElementById('updateQty');
    const qtyInput = document.getElementById('qtyInput');
    
    const modalQtyLabel = document.getElementById('modalQty');
    const cartCountLabel = document.getElementById('cartCount');
    const cartCount2Label = document.getElementById('cartCount2');
    const totalItemLabel = document.getElementById('totalItem');
    const grandTotalLabel = document.getElementById('grandTotal');

    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function syncModalQty() {
        qtyInput.value = mainQty;
        modalQtyLabel.textContent = mainQty;
        cartCountLabel.textContent = mainQty;
        cartCount2Label.textContent = mainQty;
        totalItemLabel.textContent = formatRupiah(price * mainQty);
        grandTotalLabel.textContent = formatRupiah(price * mainQty);
    }

    function openModal(e) {
        e.preventDefault();
        
        const url = this.getAttribute('href');
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .catch(err => console.error('Click tracking failed:', err));

        // Sync main page qty to modal before opening
        syncModalQty();
        cartModal.style.display = 'block';
    }

    document.getElementById('btnBuyNow').addEventListener('click', openModal);
    document.getElementById('btnAddToCart').addEventListener('click', openModal);

    function closeModal() {
        cartModal.style.display = 'none';
        editSection.style.display = 'none';
    }

    editButton.addEventListener('click', function() {
        editSection.style.display = 'block';
    });

    cancelEdit.addEventListener('click', function() {
        editSection.style.display = 'none';
    });

    updateQtyBtn.addEventListener('click', function() {
        const newQty = parseInt(qtyInput.value);
        if (newQty > 0) {
            mainQty = newQty;
            mainQtyInput.value = mainQty;
            syncModalQty();
            
            fetch('{{ route("cart.updateQty") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    qty: mainQty
                })
            }).catch(err => console.error(err));
        }
        editSection.style.display = 'none';
    });
</script>

<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>

</body>
</html>
