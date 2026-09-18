                                @php 
                                    $elementId = 'textBlock_' . $textEl->id; 
                                    $isActive = $textEl->is_active ?? true;
                                @endphp
<x-microsite.blocks.text 
    :elementId="$elementId" 
    :data="$textEl" 
    :isActive="$isActive" 
/>
