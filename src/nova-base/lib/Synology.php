<?php

class Synology extends Image
{
    public const COVER = '.COVER.JPG';

    public static function url($album, $image, $size = false): string
    {
        return IMAGES_URL . '/' . $album . '/' . self::getThumb($image, $size);
    }

    public static function path($album, $image, $size = false): string
    {
        return IMAGES_DIR . '/' . $album . '/' . self::getThumb($image, $size);
    }

    public static function getThumb($image, $size = 'SM'): string
    {
        if (!$image) {
            $image = 'noimage';
        }

        return '@eaDir/' . rawurlencode($image) . '/SYNOPHOTO_THUMB_' . ($size) . '.jpg';
    }

    public static function getThumbFromUrl(string $fullFilename): string
    {
        $fullFilename = IMAGES_DIR.'/'.$fullFilename;

        return dirname($fullFilename).'/'.self::getThumb(basename($fullFilename));

    }

    public static function getAlbumFromUrl(string $fullFilename): string
    {
        return IMAGES_DIR.'/'.dirname($fullFilename);
    }

    public static function getAlbumCoverFromUrl(string $fullFilename): string
    {
        return self::getAlbumFromUrl($fullFilename)."/".self::COVER;
    }

    public static function createCoverFromUrl(string $fullFilename): void
    {
        $album  = self::getAlbumFromUrl($fullFilename);
        $thumb = self::getThumbFromUrl($fullFilename);
        $cover = self::getAlbumCoverFromUrl($fullFilename);
        echo "<pre>";
        print_r([
           $album,
           $thumb,
           $cover,
        ]);
        echo "</pre>";
        $command = "/bin/ln -sf \"$thumb\" \"$cover\"";
        echo $command."\n";
        if (!is_writable($album)) {
            throw new Exception("$album is not writable");
        }
        $output = shell_exec($command);
        print_r($output);
        //echo $result_code."\n";
    }
}
