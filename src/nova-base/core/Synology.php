<?php


class Synology extends Image
{
    public const COVER = '.COVER.JPG';

    public const TRASH_DIR = 'Corbeille';

    public const EADIR = '@eaDir';

    public const METADATA = '.METADATA.JSON';

    public const FAVORITES_KEY = 'favorites';

    public static function urlLink($album, $image, $filedata, $size = false): string
    {
        if (!preg_match('/.MOV/', $image)) {
            return self::url($album, $image, $filedata, $size);
        }
        return '/video/' . $album . '/' . $image;
    }

    public static function url($album, $image, $filedata, $size = false): string
    {
        $album = self::cleanAlbumName($album);
        $prefixDir = isset($filedata['trash']) && $filedata['trash'] ? self::TRASH_DIR . '/' : '';
        return IMAGES_URL . '/' . $prefixDir . $album . '/' . self::getThumb($image, $size);
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
        [$dir, $thumb] = self::getThumbFromUrl($fullFilename);
        $cover = self::getAlbumCoverFromUrl($fullFilename);
        self::createLink($dir, $thumb, $cover);
    }

    private static function createLink($dir, $source, $target): void
    {
        $source = urldecode($source);
        $command = "cd \"$dir\" ; /bin/ln -sf \"$source\" \"$target\"";

        $targetDir = dirname($target);
        //echo $command."<br>\n";
        /*

        echo "stat $target<br>\n";
        //var_dump(stat($target));
        echo "<br>\n";
        echo "stat $target<br>\n";
        var_dump(stat($targetDir));

        */
        if (!is_writable($targetDir)) {
            echo "dir !is_writable($targetDir)<br>\n";
        }
        if (file_exists($target) && !is_writable($target)) {
            echo "!is_writable($target)<br>\n";
        }
        //echo shell_exec("whoami");
        exec($command, $output, $result_code);
        //echo $target;
        //print_R($output);
        //echo $result_code;

        //echo "====<br>\n";
        //echo "$target<br>\n";
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

    public static function toggleFavoriteFromUrl(string $fullFilename): void
    {
        $imageName = basename($fullFilename);
        $metadata = self::getMetadata($fullFilename);
        if (isset($metadata[self::FAVORITES_KEY][$imageName])) {
            unset($metadata[self::FAVORITES_KEY][$imageName]);
        } else {
            $metadata[self::FAVORITES_KEY][$imageName] = 1;
        }
        self::saveMetadata(self::getMetadataFilename($fullFilename), $metadata);
    }

    private static function getMetadataFilename(string $fullFilename): string
    {
        $dirName = is_dir(IMAGES_DIR . '/' . $fullFilename) ? $fullFilename : dirname($fullFilename);
        return IMAGES_DIR . '/' . $dirName . '/' . self::METADATA;
    }

    public static function getMetadata(string $fullFilename, string $key = null): array
    {
        $metadataFile = self::getMetadataFilename($fullFilename);

        //echo $metadataFile . "<br>\n";
        if (!file_exists($metadataFile)) {
            self::createEmptyMetadata($metadataFile);
        }
        $metadata = json_decode(file_get_contents($metadataFile), true, 512, JSON_THROW_ON_ERROR);
        //print_r($metadata);
        //print"<br>\n";
        if ($key) {
            return $metadata[$key];
        }
        return $metadata;
    }

    private static function createEmptyMetadata(string $metadataFile): void
    {
        $emptyData = [
            self::FAVORITES_KEY => [],
        ];
        self::saveMetadata($metadataFile, $emptyData);
    }

    private static function saveMetadata(string $metadataFile, array $data): void
    {
        file_put_contents($metadataFile, json_encode($data, JSON_THROW_ON_ERROR));
    }


    public static function toggleTrashFromUrl(string $fullFilename): void
    {
        $okFile = IMAGES_DIR . '/' . $fullFilename;
        $trashFile = IMAGES_DIR . '/' . self::TRASH_DIR . '/' . $fullFilename;
        if (file_exists($okFile) && !file_exists($trashFile)) {
            self::moveFile($okFile, $trashFile);
        } else if (!file_exists($okFile) && file_exists($trashFile)) {
            self::moveFile($trashFile, $okFile);
        } else if (file_exists($okFile) && file_exists($trashFile)) {
            throw new Exception("Fichier déjà dans la corbeille");
        } else {
            throw new Exception("Fichier introuvable");
        }
    }

    public static function download(string $fullFilename): void
    {
        $okFile = IMAGES_DIR . '/' . $fullFilename;
        if (file_exists($okFile)) {
            $cleanFile = self::cleanDownloadName($fullFilename);
            //$content = file_get_contents($okFile);
            //$mime = mime_content_type($okFile);
//            header("Content-Type: $mime");
            header('Content-Type: application/octet-stream');

            header('Content-Description: File Transfer');
            header("Content-Disposition: attachment; filename=\"$cleanFile\"");
            header("Content-Length: " . filesize($okFile));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            readfile($okFile);
            //echo $content;
            //exit;
        } else {
            throw new Exception("Fichier introuvable");
        }
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
        $thumbDir = dirname($imagePath) . '/' . self::EADIR . '/' . basename($imagePath);
        self::createDir($thumbDir);
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
        $thumbDir = dirname($imagePath) . '/' . self::EADIR . '/' . basename($imagePath);

        imagejpeg($image_thumb, $thumbDir . "/" . $fileName, 94);
    }

    public static function removeThumbs($imagePath): void
    {
        $thumbDir = dirname($imagePath) . '/' . self::EADIR . '/' . basename($imagePath);

        $thumbs = glob($thumbDir . '/*{jpg,jpeg,JPG,JPEG,png,PNG,mov,MOV}', GLOB_BRACE);
        foreach ($thumbs as $thumb) {
            unlink($thumb);
        }
    }

    private static function moveFile(string $fromFile, string $toFile): void
    {
        $targetDir = dirname($toFile);
        if (file_exists($targetDir) && !is_dir($targetDir)) {
            throw new Exception("Impossible de déplacer, la cible « $targetDir » est un fichier");
        }
        self::createDir($targetDir);
        self::createDir($targetDir . '/' . self::EADIR);
        $toThumbDir = dirname($toFile) . '/' . self::EADIR . '/' . basename($toFile);
        $fromThumbDir = dirname($fromFile) . '/' . self::EADIR . '/' . basename($fromFile);
        //echo "mv -i \"$fromFile\" \"$toFile\"<br>\n";
        //echo "mv -i \"$fromThumbDir\" \"$toThumbDir\"<br>\n";
        rename($fromFile, $toFile);
        rename($fromThumbDir, $toThumbDir);
    }

    private static function createDir(string $targetDir): void
    {
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $targetDir));
            }
        }
    }

    private static function cleanDownloadName(string $okFile)
    {
        $months = ['JANVIER', 'FEVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN', 'JUILLET',
            'AOUT', 'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DECEMBRE'];
        foreach ($months as $month) {
            $okFile = str_replace('.' . $month, '', $okFile);
        }
        return preg_replace('#^(\d\d\d\d)/(\d\d)/.*/(\w+.\w+)$#', '$1_$2_$3', $okFile);
    }

    public static function cleanAlbumName(string $album): string
    {
        return str_replace('+', '%2B', $album);
    }
}
