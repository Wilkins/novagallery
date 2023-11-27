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
        if (is_file($file)) {
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
}
