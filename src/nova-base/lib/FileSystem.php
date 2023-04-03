<?php

final class FileSystem
{
    private const IGNORED_DIRECTORIES = ['.', '..', 'cache', '@eaDir'];

    public static function listDirectories($dir)
    {
        $directories = [];
        if (!is_dir($dir)) {
            return [];
        }
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (in_array($file, self::IGNORED_DIRECTORIES, true)) {
                        continue;
                    }
                    $fullDir = $dir.'/'.$file;
                    if (!is_dir($fullDir)) {
                        continue;
                    }

                    $directories[] = $fullDir;
                }
                closedir($dh);
            }
            sort($directories);
        return $directories;
    }

    public static function listDirectories2($dir)
    {
        $directories = [];

        $directory = new DirectoryIterator($dir);
        foreach ($directory as $fileinfo) {
            if (in_array($fileinfo->getFilename(), self::IGNORED_DIRECTORIES, true)) {
                continue;
            }
            if (! $fileinfo->isDir()) {
                continue;
            }

            $directories[] = $dir.'/'.$fileinfo->getFilename();
        }
            sort($directories);
        return $directories;
    }
}