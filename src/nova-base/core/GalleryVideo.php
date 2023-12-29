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
        //echo "processImages";
        //echo "/usr/bin/find \"".$this->dir."\" -iname \"*.MOV\"";
        $res = shell_exec("/usr/bin/find \"".$this->dir."\" -iname \"*.MOV\"");
        $lines = explode("\n", $res);
//        print_r($lines);
        $fileLines = [];
        foreach ($lines as $line) {
            if (empty($line) || preg_match("/@eaDir/", $line)) {
                continue;
            }
            $fileLines[] = $line;
            //$video = str_replace($this->dir."/", "", $line);
            //$this->images[$video] = [Metadata::TRASH_KEY => '0', 'filetype' => 'video', 'duration' => '0'];
        }
        //print_r($fileLines);
        $this->images = $this->fileList($fileLines);
        //print_R($this->images);
    }

    public function parentAlbum($album): string
    {
        return $album;
    }
}
