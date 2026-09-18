                                @php 
                                    $elementId = 'videoBlock_' . $videoEl->id; 
                                    $isActive = $videoEl->is_active ?? true;
                                    $isAutoplay = $videoEl->is_autoplay ?? false;
                                @endphp
<x-microsite.blocks.video 
    :elementId="$elementId" 
    :data="$videoEl" 
    :isActive="$isActive" 
/>
