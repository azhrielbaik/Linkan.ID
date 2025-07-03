<div class="card-body">
    <h5 class="card-title">{{ $digitalProduct->title }}</h5>
    <p class="card-text">{{ $digitalProduct->description }}</p>
    <p class="card-text"><strong>Harga:</strong> Rp {{ number_format($digitalProduct->price, 0, ',', '.') }}</p>
    
    @if(Auth::id() !== $digitalProduct->user_id)
        <a href="{{ route('orders.create', $digitalProduct->id) }}" class="btn btn-primary">Beli Sekarang</a>
    @endif
</div> 