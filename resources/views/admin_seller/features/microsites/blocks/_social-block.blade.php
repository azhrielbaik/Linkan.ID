                                @php 
                                    $elementId = 'socialBlock_' . $socialEl->id; 
                                    $isActive = $socialEl->is_active ?? true;
                                    $platforms = is_string($socialEl->platforms) ? json_decode($socialEl->platforms, true) : ($socialEl->platforms ?? []);
                                @endphp
<x-microsite.blocks.social 
    :elementId="$elementId" 
    :data="$socialEl" 
    :isActive="$isActive" 
/>
