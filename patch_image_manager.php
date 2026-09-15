<?php

$controllers = [
    'app/Http/Controllers/ImageElementController.php',
    'app/Http/Controllers/TextElementController.php',
    'app/Http/Controllers/DigitalProductElementController.php'
];

$replacement = 'new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver())';

foreach ($controllers as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('\Intervention\Image\ImageManager::gd()', $replacement, $content);
        $content = str_replace('ImageManager::gd()', $replacement, $content);
        file_put_contents($file, $content);
        echo "Patched $file\n";
    }
}
