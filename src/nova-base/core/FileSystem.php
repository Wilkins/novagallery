<?php

final class FileSystem
{
    private const IGNORED_DIRECTORIES = ['.', '..', 'cache', 'Cache', '@eaDir', '.cache', '.cache_files.php'];

    private $dir;


    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function listDirectories(): array
    {
        $directories = [];

        $directory = new DirectoryIterator($this->dir);
        foreach ($directory as $fileInfo) {
            if (in_array($fileInfo->getFilename(), self::IGNORED_DIRECTORIES, true)) {
                continue;
            }
            if (!$fileInfo->isDir()) {
                continue;
            }

            $directories[] = $this->dir . '/' . $fileInfo->getFilename();
        }
        sort($directories);
        return $directories;
    }

    public static function rrmdir($dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object !== "." && $object !== "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object)) {
                        self::rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }

    public static function unlink($file): void
    {
        if (is_file($file) || is_link($file)) {
            unlink($file);
        }
    }

    public static function md5($file): string
    {
        if (filesize($file) > 10*1024*1024) {
            return filesize($file);
        }
        return md5_file($file);
    }

    public static function mkdir(string $targetDir): void
    {
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $targetDir));
            }
            //chown($targetDir, 'wilkins');
            //chgrp($targetDir, 'users');
        }
    }

    public static function moveFile(string $fromFile, string $toFile): void
    {
        $targetDir = dirname($toFile);
        if (file_exists($targetDir) && !is_dir($targetDir)) {
            throw new Exception("Impossible de déplacer, la cible « $targetDir » est un fichier");
        }
        if (file_exists($toFile)) {
            throw new Exception("Le fichier « $toFile » existe déjà");
        }
        self::mkdir($targetDir);
        self::mkdir($targetDir . '/' . Synology::EADIR);
        $toThumbDir = dirname($toFile) . '/' . Synology::EADIR . '/' . basename($toFile);
        $fromThumbDir = dirname($fromFile) . '/' . Synology::EADIR . '/' . basename($fromFile);
        //echo "mv -i \"$fromFile\" \"$toFile\"<br>\n";
        //echo "mv -i \"$fromThumbDir\" \"$toThumbDir\"<br>\n";
        rename($fromFile, $toFile);
        if (!file_exists($toFile)) {
            throw new Exception("Le fichier « $toFile » n'a pas pu être déplacé");
        }
        if (is_dir(dirname($fromThumbDir)) && file_exists($fromThumbDir)) {
            rename($fromThumbDir, $toThumbDir);
            if (!is_dir($toThumbDir)) {
                throw new Exception("Le répertoire « $toThumbDir » n'a pas pu être créé");
            }
        }
    }

    public static function renameFolder(string $fromDir, string $toDir): void
    {
        $fromDir = IMAGES_DIR.'/'.$fromDir;
        $baseDir = dirname($fromDir);
        $targetDir = $baseDir.'/'.$toDir;
        if (file_exists($targetDir) && !is_dir($targetDir)) {
            throw new Exception("Impossible de déplacer, la cible « $targetDir » est un fichier");
        }
        if (file_exists($targetDir)) {
            throw new Exception("Le répertoire « $targetDir » existe déjà");
        }
        //echo "mv -i \"$fromDir\" \"$targetDir\"<br>\n";
        rename($fromDir, $targetDir);
        if (!file_exists($targetDir)) {
            throw new Exception("Le répertoire « $targetDir » n'a pas pu être renommé");
        }
    }
}
