<?php

class Rotate
{
    public static function rotateleft(string $fullFilename): void
    {
        $okFile = Album::getFullFilename($fullFilename);
        self::rotateImage($okFile, 90);
        self::resetThumbs($okFile);

    }

    public static function rotateright(string $fullFilename): void
    {
        $okFile = Album::getFullFilename($fullFilename);
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
}
