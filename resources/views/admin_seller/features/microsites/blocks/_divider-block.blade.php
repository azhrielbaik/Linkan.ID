                                @php 
                                    $elementId = 'dividerBlock_' . $dividerEl->id; 
                                    $isActive = $dividerEl->is_active ?? true;
                                @endphp
<x-microsite.blocks.divider 
    :elementId="$elementId" 
    :data="$dividerEl" 
    :isActive="$isActive" 
/>
