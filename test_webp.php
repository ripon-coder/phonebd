<?php

require __DIR__ . '/vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

$manager = new ImageManager(new Driver());

// Create a simple blank image
$image = $manager->create(100, 100);
$image->fill('ff0000');

// Convert to WebP
$encoded = $image->toWebp(quality: 80);
$content = (string) $encoded;

// Check signature
if (str_starts_with($content, 'RIFF') && str_contains(substr($content, 8, 4), 'WEBP')) {
    echo "Success: Generated valid WebP data.\n";
    echo "Size: " . strlen($content) . " bytes\n";
} else {
    echo "Failure: Generated data is NOT valid WebP.\n";
    echo "First 20 bytes: " . bin2hex(substr($content, 0, 20)) . "\n";
}
