<?php
$src_path = 'public/images/iconpim.png';
$info = getimagesize($src_path);
echo "Image type: " . ($info ? image_type_to_mime_type($info[2]) : 'FAILED') . "\n";
echo "Size: " . filesize($src_path) . " bytes\n";
