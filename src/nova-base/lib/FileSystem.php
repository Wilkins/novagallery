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
}