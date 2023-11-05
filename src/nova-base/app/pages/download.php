<?php
//print_r([$favorite]);
//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

//$fullFilename = lib\core\Synology::getThumbFromUrl($favorite);

Synology::download($download);
echo '{"message": "ok"}' . "\n";
return;
