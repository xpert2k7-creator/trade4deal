<?php

$logo = __DIR__ . '/../public/images/trade4deal-logo.jpg';
$pub = __DIR__ . '/../public/images/favicons';

if (! is_dir($pub)) {
    mkdir($pub, 0755, true);
}

if (! is_file($logo)) {
    fwrite(STDERR, "Logo not found: {$logo}\n");
    exit(1);
}

$src = imagecreatefromjpeg($logo);
if ($src === false) {
    fwrite(STDERR, "Could not load logo.\n");
    exit(1);
}

$sw = imagesx($src);
$size = 680;
$ox = (int) max(0, ($sw - $size) / 2);
$oy = 8;

$crop = imagecreatetruecolor($size, $size);
$white = imagecolorallocate($crop, 255, 255, 255);
imagefill($crop, 0, 0, $white);
imagecopy($crop, $src, 0, 0, $ox, $oy, $size, $size);

foreach ([16, 32, 48, 180] as $dim) {
    $out = imagecreatetruecolor($dim, $dim);
    $bg = imagecolorallocate($out, 255, 255, 255);
    imagefill($out, 0, 0, $bg);
    imagecopyresampled($out, $crop, 0, 0, 0, 0, $dim, $dim, $size, $size);
    $name = $dim === 180 ? 'apple-touch-icon.png' : "favicon-{$dim}x{$dim}.png";
    imagepng($out, "{$pub}/{$name}");
    imagedestroy($out);
}

copy("{$pub}/favicon-32x32.png", "{$pub}/favicon.png");

$rootIco = __DIR__ . '/../public/favicon.ico';
if (is_link($rootIco)) {
    unlink($rootIco);
}
symlink('images/favicons/favicon.ico', $rootIco);

/**
 * @param  array<int, array{0: int, 1: int, 2: string}>  $images
 */
function pngToIco(array $images): string
{
    $count = count($images);
    $offset = 6 + 16 * $count;
    $ico = pack('vvv', 0, 1, $count);
    $data = '';

    foreach ($images as [$w, $h, $png]) {
        $len = strlen($png);
        $ico .= pack('CCCCvvVV', $w, $h, 0, 0, 1, 32, $len, $offset);
        $offset += $len;
        $data .= $png;
    }

    return $ico.$data;
}

$png32 = (string) file_get_contents("{$pub}/favicon-32x32.png");
$png16 = (string) file_get_contents("{$pub}/favicon-16x16.png");
file_put_contents("{$pub}/favicon.ico", pngToIco([[32, 32, $png32], [16, 16, $png16]]));

imagedestroy($crop);
imagedestroy($src);

echo "Favicons written to {$pub} (symlink: public/favicon.ico)\n";
