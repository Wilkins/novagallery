<?php
//print_r([$favorite]);
//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

Synology::deleteAlbum($album);
echo '{"message": "ok"}' . "\n";
return;
