<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Produk Digital Kamu</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 8px; padding: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">

        <h2 style="color: #ff7a00; margin-bottom: 20px;">Terima kasih, {{ $buyerName }}!</h2>

        <p>Berikut detail produk digital yang kamu beli:</p>

        <hr style="border-color: #ff7a00; margin: 20px 0;">

        <div style="margin-bottom: 15px;">
            <strong>Order ID:</strong>
            <div style="padding-left: 15px; font-weight: bold;">{{ $transaction->order_id ?? 'N/A' }}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <strong>Tanggal Pembelian:</strong>
            <div style="padding-left: 15px;">{{ isset($transaction) ? $transaction->created_at->format('d-m-Y H:i') : 'N/A' }}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <strong>Judul Produk:</strong>
            <div style="padding-left: 15px;">{{ $product->title }}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <strong>Deskripsi:</strong>
            <div style="padding-left: 15px; margin-top: 5px;">{!! $product->description !!}</div>
        </div>

        <div style="border-top: 2px dashed #ff7a00; margin: 30px 0; padding-top: 15px;">

            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <div><strong>Qty</strong></div>
                <div>{{ $transaction->qty ?? '1' }}</div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <div><strong>Harga per Qty</strong></div>
                <div>Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            </div>

            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1em;">
                <div>Total Harga</div>
                <div>Rp {{ number_format($transaction->total_price ?? $product->price, 0, ',', '.') }}</div>
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            @php
                $isUpload = ($product->deliverable_type === 'upload' || $product->platform_type === 'upload');
                
                $downloadLink = '';
                if ($isUpload) {
                    $filePath = $product->deliverable_url ?: $product->platform_file;
                    if ($filePath) {
                        $downloadLink = asset('storage/' . $filePath);
                    }
                } else {
                    $url = $product->deliverable_url ?: $product->platform_url;
                    if ($url && !preg_match("~^(?:f|ht)tps?://~i", $url)) {
                        $url = "https://" . $url;
                    }
                    $downloadLink = $url;
                }
            @endphp
            
            @if ($isUpload)
                <p style="margin-bottom: 15px;"><strong>File Produk:</strong></p>
                @if($downloadLink)
                    <a href="{{ $downloadLink }}" target="_blank" style="display: inline-block; padding: 12px 24px; background-color: #ff7a00; color: white; text-decoration: none; font-weight: bold; border-radius: 8px;">Download File Produk</a>
                @else
                    <p style="color: #666; font-style: italic; background-color: #f3f4f6; padding: 10px; border-radius: 8px; display: inline-block;">File produk belum tersedia atau belum diunggah.</p>
                @endif
            @else
                <p style="margin-bottom: 15px;"><strong>Akses Produk:</strong></p>
                @if($downloadLink)
                    <a href="{{ $downloadLink }}" target="_blank" style="display: inline-block; padding: 12px 24px; background-color: #ff7a00; color: white; text-decoration: none; font-weight: bold; border-radius: 8px;">Akses Produk Sekarang</a>
                @else
                    <p style="color: #666; font-style: italic; background-color: #f3f4f6; padding: 10px; border-radius: 8px; display: inline-block;">Tautan akses belum tersedia.</p>
                @endif
            @endif
        </div>

        <hr style="border-color: #ff7a00; margin: 20px 0;">

        <p>Kalau ada pertanyaan, balas saja email ini ya.</p>
          <p>sense.xj@gmail.com</p>
        <p style="margin-top: 30px;">Salam hangat,<br><strong>LINKAN.ID</strong></p>
    </div>
</body>
</html>
