<?php

/*
echo "Size : $size<br>\n";
echo "Allow : ".implode(',', Site::config('allowedImageSizes'))."<br>\n";
echo "core\Image : $image<br>\n";
echo "Album: $album<br>\n";
*/

if(in_array($size, Site::config('allowedImageSizes'), true)){
  Image::render($album, $image, $size, Site::config('imageCache'));
} else {
  header('HTTP/1.0 404 Not Found');
}
