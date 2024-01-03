<?php


/**
 * Images
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 * @uses GImage by Jose Quintana <https://git.io/joseluisq>
 * to disable cache just set cache to 'false' on initialization
 **/
class Image
{

    private static $filePath;
    private static $original;
    private static $cache;
    private static $cacheDirRoot;
    private static $cacheDir;
    private static $cacheFile;
    private static $width = false;
    private static $height = false;

    private static function initialize($album, $image, $size = false, $cache = true)
    {
        // set path & name vars
        self::$filePath = Album::getFullFilename($album);
        self::$original = self::$filePath . '/' . $image;
        //echo "original ; ".self::$original."\n";
        self::$cache = $cache;
        self::$cacheDirRoot = self::$filePath . '/cache';
        self::$cacheDir = self::$cacheDirRoot . '/' . $size;
        self::$cacheFile = self::$cacheDir . '/' . $image;

        // set size
        if ($size) {
            $size = explode('x', $size);
            if (isset($size[0]) && is_numeric($size[0])) {
                self::$width = $size[0];
            }

            if (isset($size[1]) && is_numeric($size[1])) {
                self::$height = $size[1];
            }
        }


    }

    /************
     * method to get image url based on size
     * @param string $album - name of the album
     * @param string $image - file name of image
     * @param numeric $width - optional resize width
     * @param numeric $height - optional resize height
     *
     * @return string URL - url of (resized) image
     ************/
    public static function url($album, $image, $filedata, $size = false)
    {
        // split album name if is in sub dir because slash should't be encoded with rawurlencode
        if (strpos($album, '/')) {
            $pathArray = explode('/', $album);
            $path = '';
            foreach ($pathArray as $value) {
                if ($path) {
                    $path = rawurlencode($value);
                } else {
                    $path = $path . '/' . rawurlencode($value);
                }
            }
        } else {
            $album = rawurlencode($album);
        }


        // split image name if is in sub dir because contains sub dirs
        if (strpos($image, '/')) {
            $pathArray = explode('/', $image);
            $image = array_pop($pathArray); // remove last entry from array because, it's the file
            foreach ($pathArray as $value) {
                $album .= '/' . rawurlencode($value);
            }
        }

        $url = IMAGES_URL . '/' . $album . '/cache/';

        if ($size) {
            $url .= $size . '/';
        }

        if (!$image) {
            $image = 'noimage';
        }

        return $url . rawurlencode($image);

    }


    public static function name($image): string
    {
        $name = pathinfo($image, PATHINFO_FILENAME);
        $name = str_replace(array('_', '+', '-'), ' ', $name);
        $name = ucwords($name);
        return $name;
    }


    /******
     * method to get (resized) image
     * check if image already exists else create image
     * this method uses GImage from José Luis Quintana <https://git.io/joseluisq>
     *
     * @param string $album - album name
     * @param string $image - image file name
     * @param string $size (optional) - size
     * @return file stream - image as file stream
     *
     *****/
    public static function render($album, $image, $size = false, $cache = true, $hide404image = true)
    {
        self::initialize($album, $image, $size, $cache);

        //exit;
        // check of original exisits
        if (!file_exists(self::$original)) {
            self::notFound($hide404image);
            exit;
        }

        // load image processing
        echo file_get_contents(self::$original);
        exit;
    }

    private static function notfound($hide404image = true)
    {
        header('HTTP/1.0 404 Not Found');
        if ($hide404image) {
            echo '404 Not Found';
            exit;
        }
        exit;
    }
}
