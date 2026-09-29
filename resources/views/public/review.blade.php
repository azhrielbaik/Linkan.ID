@php
    if (!function_exists('resolveProductImageUrl')) {
        function resolveProductImageUrl($path) {
            if (!$path) return 'https://via.placeholder.com/600x600?text=No+Image';
            if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
            return asset('storage/' . $path);
        }
    }

    $product = $transaction->product;
    $images = [];
    if ($product) {
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
    }
    $productImage = count($images) > 0 ? resolveProductImageUrl($images[0]) : null;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Your opinion matters to us! — Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Linkan Orange Accent Palette */
            --primary: #f97316;
            --primary-hover: #ea580c;
            --primary-gradient: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            --primary-glow: rgba(249, 115, 22, 0.35);
            --primary-light: #fff7ed;
            --primary-border: #fed7aa;
            
            /* Light Theme Tokens */
            --bg-page: #f1f4f9;
            --bg-card: #ffffff;
            --bg-card-subtle: #f8fafc;
            --bg-input: #f8fafc;
            --border-card: rgba(226, 232, 240, 0.95);
            --border-input: #e2e8f0;
            --text-heading: #0f172a;
            --text-sub: #64748b;
            --text-muted: #94a3b8;
            --card-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(15, 23, 42, 0.05);
            
            /* Star Colors */
            --star-inactive: #cbd5e1;
            --star-active: #f59e0b;
            --star-active-glow: rgba(245, 158, 11, 0.35);
            
            /* Status */
            --success: #10b981;
            --success-light: #ecfdf5;
            --danger: #ef4444;
            --danger-light: #fef2f2;
        }

        /* Dark Theme Tokens (Matching the dark card in the mockup!) */
        [data-theme="dark"] {
            --bg-page: #0b0f19;
            --bg-card: #1e2638;
            --bg-card-subtle: #161d2d;
            --bg-input: #141a29;
            --border-card: #2d3748;
            --border-input: #334155;
            --text-heading: #f8fafc;
            --text-sub: #94a3b8;
            --text-muted: #64748b;
            --card-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 1px 1px rgba(255, 255, 255, 0.05);
            --primary-light: rgba(249, 115, 22, 0.12);
            --primary-border: rgba(249, 115, 22, 0.3);
            --star-inactive: #334155;
            --star-active: #f59e0b;
            --star-active-glow: rgba(245, 158, 11, 0.45);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.2s ease;
        }

        body {
            background-color: var(--bg-page);
            background-image: 
                radial-gradient(at 10% 10%, rgba(249, 115, 22, 0.08) 0px, transparent 40%),
                radial-gradient(at 90% 90%, rgba(234, 88, 12, 0.06) 0px, transparent 40%);
            background-attachment: fixed;
            color: var(--text-heading);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 16px 48px 16px;
        }

        .container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
        }

        /* Top Navigation Bar / Theme Switcher */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 0 4px;
        }

        .brand-logo-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .brand-logo-link:hover {
            transform: scale(1.02);
        }

        .brand-logo {
            height: 32px;
            width: auto;
            object-fit: contain;
        }

        .theme-toggle-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            color: var(--text-sub);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
            transform: rotate(15deg);
        }

        /* Order & Product Snapshot Card */
        .order-info-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 18px 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 16px;
        }

        .order-info-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-input);
            margin-bottom: 14px;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #10b981;
            background: var(--success-light);
            border: 1px solid rgba(16, 185, 129, 0.25);
            padding: 4px 10px;
            border-radius: 9999px;
        }

        [data-theme="dark"] .verified-badge {
            background: rgba(16, 185, 129, 0.12);
        }

        .order-id-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-sub);
            background: var(--bg-card-subtle);
            border: 1px solid var(--border-input);
            padding: 4px 10px;
            border-radius: 8px;
            cursor: pointer;
            user-select: none;
            transition: all 0.15s ease;
        }

        .order-id-pill:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        .order-id-pill .copy-feedback {
            font-size: 11px;
            color: var(--primary);
            font-weight: 800;
            display: none;
        }

        /* Product Details Row */
        .product-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-img-box {
            width: 58px;
            height: 58px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--bg-card-subtle);
            border: 1px solid var(--border-input);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-img-placeholder {
            font-size: 22px;
            color: var(--primary);
        }

        .product-info {
            flex: 1;
            min-width: 0;
        }

        .product-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-meta-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 12.5px;
            color: var(--text-sub);
            align-items: center;
        }

        .price-tag {
            font-weight: 700;
            color: var(--primary);
        }

        .buyer-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: var(--text-sub);
            font-weight: 500;
        }

        .buyer-name-highlight {
            font-weight: 700;
            color: var(--text-heading);
        }

        /* ========================================================
           MAIN REVIEW CARD (Pixel-perfect to user mockup image)
           ======================================================== */
        .review-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            padding: 36px 30px 28px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Top Accent Bar */
        .review-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        /* Header Typography */
        .review-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.3px;
            margin-bottom: 10px;
        }

        .review-subtitle {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-sub);
            margin-bottom: 24px;
            line-height: 1.4;
        }

        /* 5-Star Rating Picker Section */
        .stars-container {
            margin-bottom: 24px;
        }

        .stars-row {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
        }

        .star-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--star-inactive);
            font-size: 38px;
            transition: transform 0.15s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.15s ease, filter 0.15s ease;
            outline: none;
        }

        .star-item:hover,
        .star-item:focus-visible {
            transform: scale(1.22);
        }

        .star-item.active,
        .star-item.hovered {
            color: var(--star-active);
            filter: drop-shadow(0 2px 8px var(--star-active-glow));
        }

        /* Scale Labels: AWFUL vs BRILLIANT (Shown in user's mockup) */
        .star-scale-labels {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px 0 12px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .rating-sentiment-badge {
            margin-top: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            min-height: 20px;
            transition: all 0.2s ease;
        }

        /* Message / Comment Textarea (Rounded box in mockup) */
        .form-message-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .message-textarea {
            width: 100%;
            min-height: 90px;
            padding: 14px 16px;
            background: var(--bg-input);
            border: 1.5px solid var(--border-input);
            border-radius: 14px;
            font-size: 13.5px;
            color: var(--text-heading);
            resize: none;
            outline: none;
            transition: all 0.2s ease;
        }

        .message-textarea::placeholder {
            color: var(--text-muted);
            font-size: 13.5px;
        }

        .message-textarea:focus {
            background: var(--bg-card);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
        }

        .textarea-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 4px;
            font-size: 11.5px;
            color: var(--text-muted);
        }

        /* Submit Button: Rate now (Pill shaped, vibrant orange accent) */
        .btn-rate-now {
            width: 100%;
            padding: 14px 24px;
            background: var(--primary-gradient);
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 6px 18px var(--primary-glow);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.2px;
        }

        .btn-rate-now:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(249, 115, 22, 0.45);
        }

        .btn-rate-now:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-rate-now:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Bottom "Maybe later" Link (Exact match to mockup) */
        .action-maybe-later {
            margin-top: 18px;
            text-align: center;
        }

        .link-maybe-later {
            display: inline-block;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-sub);
            text-decoration: none;
            transition: color 0.15s ease;
            cursor: pointer;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .link-maybe-later:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        /* Alert Box */
        .alert-box {
            background: var(--danger-light);
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 16px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* State: Success / Already Reviewed */
        .success-state-box {
            padding: 10px 4px 6px 4px;
            text-align: center;
        }

        .success-icon-badge {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--success-light);
            color: var(--success);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0.1);
        }

        .review-quote-card {
            background: var(--bg-card-subtle);
            border: 1px solid var(--border-input);
            border-radius: 14px;
            padding: 16px;
            margin: 18px 0;
            text-align: left;
        }

        .quote-stars {
            color: var(--star-active);
            font-size: 16px;
            margin-bottom: 6px;
        }

        .quote-text {
            font-size: 13.5px;
            color: var(--text-heading);
            font-style: italic;
            line-height: 1.5;
        }

        /* Bottom Resolution / Dispute Bar */
        .resolution-bar {
            margin-top: 20px;
            text-align: center;
            font-size: 12.5px;
            color: var(--text-sub);
        }

        .resolution-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .resolution-link:hover {
            text-decoration: underline;
        }

        .footer-copyright {
            margin-top: 18px;
            text-align: center;
            font-size: 11.5px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Top Bar: Logo & Theme Switcher -->
        <header class="top-bar">
            <a href="{{ url('/') }}" class="brand-logo-link" title="Linkan.ID">
                <img src="{{ asset('images/Logo.png') }}" alt="LINKAN.ID" class="brand-logo" onerror="this.src='{{ asset('images/logo.png') }}'">
            </a>
            <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Ganti Tema">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
        </header>

        <!-- Order & Product Information Card (Required by user) -->
        <section class="order-info-card" aria-label="Informasi Pesanan">
            <div class="order-info-header">
                <span class="verified-badge">
                    <i class="fas fa-check-circle"></i> Pembelian Terverifikasi
                </span>
                <div class="order-id-pill" onclick="copyOrderId('{{ $transaction->order_id }}')" title="Klik untuk menyalin Order ID">
                    <span>Order:</span>
                    <strong>#{{ $transaction->order_id }}</strong>
                    <i class="far fa-copy" id="copyIcon"></i>
                    <span class="copy-feedback" id="copyFeedback">Tersalin!</span>
                </div>
            </div>

            <div class="product-row">
                <div class="product-img-box">
                    @if($productImage)
                        <img src="{{ $productImage }}" alt="{{ $product->title ?? 'Produk' }}" class="product-img">
                    @else
                        <div class="product-img-placeholder">
                            <i class="fas fa-box"></i>
                        </div>
                    @endif
                </div>
                <div class="product-info">
                    <h2 class="product-title" title="{{ $product->title ?? 'Produk Digital' }}">
                        {{ $product->title ?? 'Produk Digital' }}
                    </h2>
                    <div class="product-meta-tags">
                        <span class="price-tag">
                            Rp {{ number_format($transaction->total_price ?? ($product->price ?? 0), 0, ',', '.') }}
                            @if(($transaction->qty ?? 1) > 1)
                                <small>({{ $transaction->qty }}x)</small>
                            @endif
                        </span>
                        <span>•</span>
                        <span class="buyer-tag" title="{{ $transaction->buyer_email }}">
                            <i class="fas fa-user-circle"></i> Penerima:
                            <span class="buyer-name-highlight">{{ $transaction->buyer_name ?: 'Pembeli' }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Review Card (Styled directly after the mockup image) -->
        <main class="review-card">
            @if($alreadyReviewed || session('review_success') || session('already_reviewed'))
                <!-- State: Review Sudah Dikirim / Berhasil -->
                <div class="success-state-box">
                    <div class="success-icon-badge">
                        <i class="fas fa-check"></i>
                    </div>
                    <h1 class="review-title">
                        {{ session('review_success') ? 'Terima Kasih!' : 'Ulasan Telah Diterima' }}
                    </h1>
                    <p class="review-subtitle">
                        {{ session('review_success') 
                            ? 'Pendapat Anda sangat berarti bagi kami dan membantu meningkatkan kualitas layanan.' 
                            : 'Anda sudah pernah memberikan ulasan untuk pesanan ini.' }}
                    </p>

                    @php
                        $displayReview = $existingReview ?? \App\Models\ProductReview::where('order_id', $transaction->order_id)->first();
                    @endphp

                    @if($displayReview)
                        <div class="review-quote-card">
                            <div class="quote-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $displayReview->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                                <strong style="font-size: 13px; margin-left: 6px; color: var(--text-heading);">{{ $displayReview->rating }}/5</strong>
                            </div>
                            @if($displayReview->comment)
                                <p class="quote-text">“{{ $displayReview->comment }}”</p>
                            @endif
                        </div>
                    @endif

                    <div style="margin-top: 22px;">
                        <a href="{{ url('/') }}" class="btn-rate-now" style="text-decoration: none;">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>

            @else
                <!-- Form Ulasan Baru (Exact Match to Reference Mockup) -->
                <h1 class="review-title">Your opinion matters to us!</h1>
                <p class="review-subtitle">Bagaimana kualitas produk yang Anda terima?</p>

                @if(isset($errors) && $errors->any())
                    <div class="alert-box">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('public.review.store', ['locale' => app()->getLocale(), 'token' => $token]) }}" id="reviewForm" onsubmit="handleFormSubmit(event)">
                    @csrf

                    <!-- 5-Star Interactive Rating Section -->
                    <div class="stars-container">
                        <div class="stars-row" id="starPicker" role="radiogroup" aria-label="Beri Nilai Produk">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star-item" data-value="{{ $i }}" role="radio" aria-checked="false" tabindex="0" title="{{ $i }} Bintang">
                                    <i class="fas fa-star"></i>
                                </span>
                            @endfor
                        </div>

                        <!-- Labels Awful & Brilliant from mockup -->
                        <div class="star-scale-labels">
                            <span>Awful</span>
                            <span>Brilliant</span>
                        </div>

                        <!-- Dynamic Sentiment Badge -->
                        <div id="ratingSentiment" class="rating-sentiment-badge">Pilih rating bintang Anda</div>
                        <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', '') }}" required>
                    </div>

                    <!-- Message Textarea ("Leave a message, if you want") -->
                    <div class="form-message-group">
                        <textarea 
                            name="comment" 
                            id="commentInput" 
                            class="message-textarea" 
                            maxlength="500"
                            placeholder="Leave a message, if you want"
                            oninput="updateCharCount(this)">{{ old('comment', '') }}</textarea>
                        <div class="textarea-footer">
                            <span id="charCounter">0 / 500</span>
                        </div>
                    </div>

                    <!-- Submit Button ("Rate now") -->
                    <button type="submit" id="btnSubmitReview" class="btn-rate-now">
                        <span id="btnSubmitText">Rate now</span>
                    </button>

                    <!-- Bottom Link ("Maybe later") -->
                    <div class="action-maybe-later">
                        <a href="{{ url('/') }}" class="link-maybe-later" onclick="handleMaybeLater(event)">
                            Maybe later
                        </a>
                    </div>
                </form>
            @endif
        </main>

        <!-- Help / Resolution Support Link -->
        <aside class="resolution-bar">
            Mengalami masalah dengan pesanan ini? 
            <a href="{{ route('public.dispute.create', ['locale' => app()->getLocale()]) }}" class="resolution-link">
                Ajukan Komplain <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            </a>
        </aside>

        <footer class="footer-copyright">
            &copy; {{ date('Y') }} Linkan.ID. All rights reserved.
        </footer>
    </div>

    <script>
        // Star sentiment labels
        const ratingSentiments = {
            1: 'Awful (Sangat Buruk) ⭐',
            2: 'Poor (Kurang Memuaskan) ⭐⭐',
            3: 'Average (Cukup Baik) ⭐⭐⭐',
            4: 'Good (Bagus & Bermanfaat) ⭐⭐⭐⭐',
            5: 'Brilliant (Luar Biasa!) ⭐⭐⭐⭐⭐'
        };

        const stars = document.querySelectorAll('#starPicker .star-item');
        const ratingInput = document.getElementById('ratingInput');
        const ratingSentiment = document.getElementById('ratingSentiment');
        const commentInput = document.getElementById('commentInput');
        const charCounter = document.getElementById('charCounter');
        const btnSubmit = document.getElementById('btnSubmitReview');
        const btnSubmitText = document.getElementById('btnSubmitText');

        // Restore rating if previous validation failed or old() exists
        if (ratingInput && ratingInput.value) {
            applyStarRating(parseInt(ratingInput.value));
        }

        if (commentInput && charCounter) {
            updateCharCount(commentInput);
        }

        stars.forEach(star => {
            // Hover preview
            star.addEventListener('mouseenter', function() {
                const val = parseInt(this.dataset.value);
                highlightStars(val);
                if (ratingSentiment) {
                    ratingSentiment.textContent = ratingSentiments[val] || '';
                }
            });

            // Hover out
            star.addEventListener('mouseleave', function() {
                const currentVal = parseInt(ratingInput.value) || 0;
                highlightStars(currentVal);
                if (ratingSentiment) {
                    ratingSentiment.textContent = currentVal > 0 ? ratingSentiments[currentVal] : 'Pilih rating bintang Anda';
                }
            });

            // Click select
            star.addEventListener('click', function() {
                const val = parseInt(this.dataset.value);
                applyStarRating(val);
            });

            // Keyboard accessibility
            star.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    applyStarRating(parseInt(this.dataset.value));
                }
            });
        });

        function highlightStars(val) {
            stars.forEach((s, idx) => {
                s.classList.toggle('hovered', idx < val);
            });
        }

        function applyStarRating(val) {
            if (!ratingInput) return;
            ratingInput.value = val;
            stars.forEach((s, idx) => {
                const isSelected = idx < val;
                s.classList.toggle('active', isSelected);
                s.setAttribute('aria-checked', isSelected ? 'true' : 'false');
            });
            if (ratingSentiment) {
                ratingSentiment.textContent = ratingSentiments[val] || '';
            }
        }

        function updateCharCount(el) {
            if (!charCounter) return;
            const len = el.value.length;
            charCounter.textContent = `${len} / 500`;
            if (len >= 480) {
                charCounter.style.color = '#ef4444';
            } else {
                charCounter.style.color = 'var(--text-muted)';
            }
        }

        function handleFormSubmit(e) {
            const currentVal = parseInt(ratingInput ? ratingInput.value : 0) || 0;
            if (currentVal < 1 || currentVal > 5) {
                e.preventDefault();
                alert('Silakan pilih rating bintang terlebih dahulu (1 - 5 bintang).');
                return false;
            }

            if (btnSubmit) {
                btnSubmit.disabled = true;
                if (btnSubmitText) {
                    btnSubmitText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                }
            }
            return true;
        }

        function handleMaybeLater(e) {
            // If user has history, optionally go back or continue to root
            if (window.history.length > 1) {
                e.preventDefault();
                window.history.back();
            }
        }

        // Copy Order ID with tooltip feedback
        function copyOrderId(orderId) {
            navigator.clipboard.writeText(orderId).then(() => {
                const icon = document.getElementById('copyIcon');
                const feedback = document.getElementById('copyFeedback');
                if (icon && feedback) {
                    icon.style.display = 'none';
                    feedback.style.display = 'inline';
                    setTimeout(() => {
                        icon.style.display = 'inline';
                        feedback.style.display = 'none';
                    }, 2000);
                }
            }).catch(err => {
                console.warn('Clipboard write failed:', err);
            });
        }

        // Theme Toggle (Light / Dark mode as seen in mockup!)
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        function setTheme(theme) {
            htmlElement.setAttribute('data-theme', theme);
            localStorage.setItem('linkan_review_theme', theme);
            if (themeIcon) {
                themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }

        // Initialize saved theme or system preference
        const savedTheme = localStorage.getItem('linkan_review_theme') || 
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        setTheme(savedTheme);

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                const currentTheme = htmlElement.getAttribute('data-theme');
                setTheme(currentTheme === 'dark' ? 'light' : 'dark');
            });
        }
    </script>
</body>
</html>
