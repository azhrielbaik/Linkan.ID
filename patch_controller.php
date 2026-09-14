<?php
$file = 'app/Http/Controllers/AdminSeller/DigitalProductController.php';
$content = file_get_contents($file);

if (strpos($content, 'public function show(') === false) {
    $insert = "
    public function show(\$id)
    {
        \$user = Auth::user();
        \$product = \App\Models\DigitalProduct::where('id', \$id)->where('user_id', \$user->id)->firstOrFail();
        return view('admin_seller.features.digital_products.show', compact('product', 'user'));
    }
";
    // insert right before update method
    $content = str_replace('public function update(', $insert . '    public function update(', $content);
    file_put_contents($file, $content);
}
