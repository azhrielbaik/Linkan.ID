<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Purchased Content</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body { background-color: #f5f6fa; }
        .container {
            display: flex;
            min-height: 100vh;
            gap: 0;
            padding: 0;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            min-width: 0; /* Untuk mencegah overflow */
            margin-left: 250px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        .notification-icon {
            background-color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .notification-icon i {
            color: #333;
            font-size: 16px;
        }
        .my-linkan-header {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .my-linkan-url {
            background: #f5f5f5;
            padding: 8px 15px;
            border-radius: 5px;
            flex-grow: 1;
            color: #666;
            font-weight: 600;
        }
        .share-button {
            background: none;
            border: none;
            color: #FF9040;
            cursor: pointer;
            padding: 5px;
            font-size: 18px;
        }
        .filter-sort-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }
        .filter-sort-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px 12px;
            color: #666;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .filter-sort-btn:hover {
            background: #f5f5f5;
        }
        .search-bar {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }
        .search-bar input {
            width: 180px;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px 10px;
            font-size: 14px;
        }
        .search-bar button {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px 10px;
            color: #666;
            font-size: 14px;
            cursor: pointer;
        }
        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="container">
    @include('homeadminS.sidebar.sidebar')
    <div class="main-content">
        <div class="header">
            <h1>Purchased Content</h1>
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
            </div>
        </div>
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
</div>
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copied to clipboard!');
    }, function(err) {
        alert('Failed to copy link');
    });
}
</script>
</body>
</html>
