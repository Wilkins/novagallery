<?php
//print_r([$favorite]);
//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

Synology::toggleFavoriteFromUrl($favorite);
echo '{"message": "ok"}' . "\n";
return;
