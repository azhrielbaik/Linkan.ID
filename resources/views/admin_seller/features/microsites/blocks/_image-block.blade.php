                                @php 
                                    $elementId = 'imageBlock_' . $imageEl->id; 
                                    $isActive = $imageEl->is_active ?? true;
                                @endphp
<x-microsite.blocks.image 
    :elementId="$elementId" 
    :data="$imageEl" 
    :isActive="$isActive" 
/>
