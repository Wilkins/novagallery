<?php

/**
 * core\lib\core\Gallery - List Images and Albums
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @version 1.1.1
 * @link https://novagallery.org
 * to disable cache just set maxCacheAge to 'false' on initialization
 **/
class GalleryVideo extends Gallery
{

    protected function listAlbums(): void
    {
        $this->albums = [];
    }

    protected function processImages(): void
    {
        $nameParameters = array_map(
            function ($format) {
                return ' -iname "*.'.$format.'"';
            },
            FileType::getVideoFormats()
        );
        $nameArguments = " \( ".implode(' -o ', $nameParameters)." \) ";
        $command = "/usr/bin/find \"".$this->dir."\" ".$nameArguments;
        $res = shell_exec($command);
        $lines = explode("\n", $res);
        $fileLines = [];
        foreach ($lines as $line) {
            if (empty($line) || preg_match("/@eaDir/", $line)) {
                continue;
            }
            $fileLines[] = $line;
        }
        $this->images = $this->fileList($fileLines);
    }

    public function parentAlbum($album): string
    {
        return $album;
    }
}
