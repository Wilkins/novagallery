<?php
//print_r([$favorite]);
//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

Metadata::toggleFavoriteFromUrl($favorite);
echo '{"message": "ok"}' . "\n";
return;
