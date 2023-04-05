<?php
/**
 * Cache
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 * @uses GImage by Jose Quintana <https://git.io/joseluisq>
 * to disable cache just set cache to 'false' on initialization
 **/

class Cache
{

    protected static $cacheDir = '';
    protected static $cacheFile = '.cache_files.php';

    protected static function readCache($dir, $maxAge): novaGallery
    {
        $cacheFile = $dir . '/' . self::$cacheDir . '/' . self::$cacheFile;
        if (file_exists($cacheFile)) {
            $age = time() - filemtime($cacheFile);
            if ($age > $maxAge) {
                return false;
            }

            $content = file($cacheFile);
            unset($content[0]); // Remove first security line (<?php die();)
            $content = implode($content); // Regenerate JSON
            $content = json_decode($content, true);
            $this->images = $content['images'];
            $this->albums = $content['albums'];
            return true;
        } else {
            return false;
        }
    }

    protected static function writeCache($dir)
    {
        $cacheDir = $dir . '/' . $this->cacheDir;
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $cacheFile = $cacheDir . '/' . $this->cacheFile;
        $content = ['images' => $this->images, 'albums' => $this->albums];
        $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $data = '<?php die(); ?>' . PHP_EOL;
        $data .= $content;
        file_put_contents($cacheFile, $data, LOCK_EX); // LOCK_EX flag prevents that anyone else is writing to the file at the same time
        return true; // only true because if cache doesn't work, it also works (just only without cache)
    }


}
