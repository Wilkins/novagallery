<?php

class Synology extends Image
{

    public static function urlLink($album, $image, $filedata, $size = false): string
    {
        if (FileType::isVideo($image)) {
            return '/video/' . $album . '/' . $image;
        }
        return self::url($album, $image, $filedata, $size);
    }

    public static function url($album, $image, $filedata, $size = false): string
    {
        $album = self::cleanAlbumName($album);
        $prefixDir = isset($filedata[Metadata::TRASH_KEY]) && $filedata[Metadata::TRASH_KEY] ? File::TRASH_DIR . '/' : '';
        if ($size === 'SM' && ! file_exists(self::path($album, $image, $size))) {
            $size = 'M';
        }
        return IMAGES_URL . '/' . $prefixDir . $album . '/' . self::getThumb($image, $size);;
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
        if ($size === 'XL') {
            return $image;
        }
        $imageArray = explode('/', $image);
        $imageArray = array_map('rawurlencode', $imageArray);
        $file = array_pop($imageArray);
        $imageArray[] = File::EADIR;
        $imageArray[] = $file;
        $image = implode('/', $imageArray);
        $image .= '/SYNOPHOTO_THUMB_' . ($size) . '.jpg';
        return $image;
        /*
        return File::EADIR . '/' . rawurlencode($image) . '/SYNOPHOTO_THUMB_' . ($size) . '.jpg';
        */
    }

    public static function getThumbFromUrl(string $fullFilename): array
    {
        $fullFilename = IMAGES_DIR . '/' . $fullFilename;

        $dirName = dirname($fullFilename);
        $thumb = self::getThumb(basename($fullFilename));
        if (!file_exists($dirName.'/'.$thumb)) {
            $thumb = self::getThumb(basename($fullFilename), 'M');
        }
        return [$dirName, $thumb];
    }

    public static function getAlbumFromUrl(string $fullFilename): string
    {
        return IMAGES_DIR . '/' . dirname($fullFilename);
    }

    public static function getAlbumCoverFromUrl(string $fullFilename): string
    {
        return self::getAlbumFromUrl($fullFilename) . "/" . File::COVER;
    }

    public static function createCoverFromUrl(string $fullFilename): void
    {
        if (self::isAlbum($fullFilename)) {
            $fullAlbum = self::getAlbumDir($fullFilename);
            [$dir, $subCover] = [dirname($fullAlbum), basename($fullAlbum) . '/' . File::COVER];
            $targetCover = self::getAlbumFromurl($fullFilename) . '/' . File::COVER;
            FileSystem::createLink($dir, $subCover, $targetCover);

            return;
        }
        [$dir, $thumb] = self::getThumbFromUrl($fullFilename);
        $cover = self::getAlbumCoverFromUrl($fullFilename);
        FileSystem::createLink($dir, $thumb, $cover);
    }

    private static function getAlbumDir(string $fullFilename): string
    {
        return IMAGES_DIR . '/' . $fullFilename;
    }

    private static function isAlbum(string $fullFilename): bool
    {
        return is_dir(self::getAlbumDir($fullFilename));
    }

    public static function cleanAlbumName(string $album): string
    {
        return str_replace('+', '%2B', $album);
    }

    public static function cleanAlbumTitle(string $album): string
    {
        return ucwords(str_replace('_', ' ', $album));
    }

    public static function moveTo($fullFilename, $destination)
    {
        $currentFile = IMAGES_DIR . '/' . $fullFilename;
        $dirs = explode('/', $fullFilename);
        if (File::isMonthDir($dirs[1])
            || File::isSpecialDir($dirs[1])) {
            $newFile = IMAGES_DIR . '/' . implode("/", [$dirs[0], $dirs[1], $destination, basename($fullFilename)]);
            FileSystem::moveFile($currentFile, $newFile);
        }
    }

}
