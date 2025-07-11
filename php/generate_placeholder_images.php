<?php
// Create a 400x400 image
$width = 400;
$height = 400;

// Colors
$bg_color = [240, 240, 240]; // Light gray
$text_color = [100, 100, 100]; // Dark gray

// Categories with their icons
$categories = [
    'hammer' => 'fa-hammer',
    'drill' => 'fa-bolt',
    'shovel' => 'fa-leaf',
    'pipe-wrench' => 'fa-faucet',
    'multimeter' => 'fa-plug',
    'goggles' => 'fa-hard-hat',
    'screws' => 'fa-screw',
    'laser-level' => 'fa-tools',
    'caliper' => 'fa-ruler',
    'utility-knife' => 'fa-cut',
    'socket-set' => 'fa-car',
    'paint-roller' => 'fa-paint-roller',
    'toolbox' => 'fa-box',
    'welder' => 'fa-fire',
    'chisels' => 'fa-tree'
];

// Create images directory if it doesn't exist
if (!file_exists('../images/products')) {
    mkdir('../images/products', 0777, true);
}

// Generate an image for each category
foreach ($categories as $filename => $icon) {
    // Create image
    $image = imagecreatetruecolor($width, $height);
    
    // Set colors
    $bg = imagecolorallocate($image, $bg_color[0], $bg_color[1], $bg_color[2]);
    $text = imagecolorallocate($image, $text_color[0], $text_color[1], $text_color[2]);
    
    // Fill background
    imagefill($image, 0, 0, $bg);
    
    // Add text
    $text = "Product Image\n" . ucwords(str_replace('-', ' ', $filename));
    $font_size = 5;
    $text_box = imagettfbbox($font_size, 0, 'arial.ttf', $text);
    $text_width = abs($text_box[4] - $text_box[0]);
    $text_height = abs($text_box[5] - $text_box[1]);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    
    // Draw text
    imagestring($image, $font_size, $x, $y, $text, $text);
    
    // Save image
    imagejpeg($image, "../images/products/{$filename}.jpg", 90);
    imagedestroy($image);
}

echo "Generated placeholder images for all product categories.\n";
?> 