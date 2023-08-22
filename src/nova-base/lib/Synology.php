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

    public static function getThumbFromUrl(string $fullFilename): array
    {
        $fullFilename = IMAGES_DIR . '/' . $fullFilename;

        return [dirname($fullFilename), self::getThumb(basename($fullFilename))];

    }

    public static function getAlbumFromUrl(string $fullFilename): string
    {
        return IMAGES_DIR . '/' . dirname($fullFilename);
    }

    public static function getAlbumCoverFromUrl(string $fullFilename): string
    {
        return self::getAlbumFromUrl($fullFilename) . "/" . self::COVER;
    }

    public static function createCoverFromUrl(string $fullFilename): void
    {
        if (self::isAlbum($fullFilename)) {
            $fullAlbum = self::getAlbumDir($fullFilename);
            [$dir, $subCover] = [dirname($fullAlbum), basename($fullAlbum) . '/' . self::COVER];
            $targetCover = self::getAlbumFromurl($fullFilename) . '/' . self::COVER;
            self::createLink($dir, $subCover, $targetCover);

            return;
        }
        //echo IMAGES_DIR.'/'.$fullFilename;
        [$dir, $thumb] = self::getThumbFromUrl($fullFilename);
        $cover = self::getAlbumCoverFromUrl($fullFilename);
        self::createLink($dir, $thumb, $cover);
    }

    private static function createLink($dir, $source, $target): void
    {
        $command = "cd \"$dir\" ; /bin/ln -sf \"$source\" \"$target\"";

        $targetDir = dirname($target);
        //echo $command."<br>\n";
        /*

        echo "stat $target<br>\n";
        //var_dump(stat($target));
        echo "<br>\n";
        echo "stat $target<br>\n";
        var_dump(stat($targetDir));

        if (!is_writable($targetDir)) {
            echo "!is_writable($targetDir)<br>\n";
        }
        if (!is_writable($target)) {
            echo "!is_writable($target)<br>\n";
        }
        */
        $output = shell_exec($command);
        if (file_exists($target)) {
            return;
        }
        echo $command . "<br>\n";

        throw new Exception("$target should have been created");
        //print_r($output);
        /*
        $output = shell_exec("/bin/readlink $target");
        print_r($output);
        if (!is_writable($targetDir)) {
            //throw new Exception("$targetDir is not writable");
        }
        return true;
        */
    }

    private static function getAlbumDir(string $fullFilename): string
    {
        return IMAGES_DIR . '/' . $fullFilename;
    }

    private static function isAlbum(string $fullFilename): bool
    {
        return is_dir(self::getAlbumDir($fullFilename));
    }
}
