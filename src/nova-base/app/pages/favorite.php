<?php
//print_r([$favorite]);
//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

Synology::createCoverFromUrl($favorite);
echo '{"message": "ok"}' . "\n";
return;

echo $fullFilename;
echo "<br>\n";

return;
//chdir($album);
/** @var Gallery $gallery */
$gallery = Page::data('gallery');
$album = Page::data('album');
$order = Page::data('order');
$start = microtime(true);

?>
