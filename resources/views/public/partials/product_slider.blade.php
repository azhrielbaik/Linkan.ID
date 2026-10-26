<!-- Left Column: Images & Slider -->
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
