<?php
// Just a snippet to think about how to find the microsite
$appearances = \App\Models\Appearance::where('user_id', $product->user_id)->get();
$micrositeAlias = null;
foreach ($appearances as $appearance) {
    if (str_contains($appearance->blocks_order, 'digitalproduct_' . $product->id)) {
        $micrositeAlias = $appearance->alias;
        break;
    }
}
if (!$micrositeAlias) {
    $firstAppearance = \App\Models\Appearance::where('user_id', $product->user_id)->first();
    $micrositeAlias = $firstAppearance ? $firstAppearance->alias : $product->user->username;
}
$micrositeUrl = url('/' . $micrositeAlias);
