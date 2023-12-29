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

        if (strpos($image, '/')) {
            $dirName = dirname($image) . '/' . File::EADIR . '/'. basename($image);
        } else {
            File::EADIR . '/' . rawurlencode($image);
        }

        return File::EADIR . '/' . rawurlencode($image) . '/SYNOPHOTO_THUMB_' . ($size) . '.jpg';
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

    public static function rotateleft(string $fullFilename): void
    {
        $okFile = IMAGES_DIR . '/' . $fullFilename;
        self::rotateImage($okFile, 90);
        self::resetThumbs($okFile);

    }

    public static function rotateright(string $fullFilename): void
    {
        $okFile = IMAGES_DIR . '/' . $fullFilename;
        self::rotateImage($okFile, -90);
        self::resetThumbs($okFile);

    }

    public static function rotateImage($imagePath, $angle): void
    {
        if (file_exists($imagePath)) {
            $path = realpath($imagePath);
            $source = imagecreatefromjpeg($path);
            $rotated = imagerotate($source, $angle, 0);
            imagejpeg($rotated, $path, 94);
        } else {
            throw new Exception("Fichier introuvable");
        }
    }

    public static function resetThumbs($imagePath): void
    {
        self::removeThumbs($imagePath);
        $thumbDir = dirname($imagePath) . '/' . File::EADIR . '/' . basename($imagePath);
        FileSystem::mkdir($thumbDir);
        self::createThumb($imagePath, "SYNOPHOTO_THUMB_XL.jpg", 1280);
        self::createThumb($imagePath, "SYNOPHOTO_THUMB_SM.jpg", 320);
    }

    public static function createThumb($imagePath, $fileName, $maxSize): void
    {
        $width = $maxSize;
        $height = $maxSize;

        [$width_orig, $height_orig] = getimagesize($imagePath);

        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }

        $image_thumb = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($imagePath);
        imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        $thumbDir = dirname($imagePath) . '/' . File::EADIR . '/' . basename($imagePath);

        imagejpeg($image_thumb, $thumbDir . "/" . $fileName, 94);
    }

    public static function removeThumbs($imagePath): void
    {
        $thumbDir = dirname($imagePath) . '/' . File::EADIR . '/' . basename($imagePath);

        $thumbs = glob($thumbDir . '/*' . FileType::getAcceptedFormats(), GLOB_BRACE);
        foreach ($thumbs as $thumb) {
            unlink($thumb);
        }
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
