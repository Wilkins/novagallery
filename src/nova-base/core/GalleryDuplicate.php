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
class GalleryDuplicate extends Gallery
{
    const MD5_SEPARATOR = '  ';

    protected function listAlbums(): void
    {
        $this->albums = $this->fileList([]);
    }

    protected function processImages(): void
    {
        $dirs = explode('/', $this->album);
        $md5file = implode('/', [$this->root, $dirs[0]]).'.md5sum';
        $lines = file($md5file);
        $duplicates = [];
        foreach ($lines as $line) {
            $line = trim($line);
            [$md5, $file] = explode(self::MD5_SEPARATOR, $line, 2);
            if (!preg_match("#^".$this->album."#", $file)) {
                continue;
            }
            if (!isset($duplicates[$md5])) {
                $duplicates[$md5] = [];
            }
            $shortFile = str_replace($this->album.'/', '', $file);
            $duplicates[$md5][] = $shortFile;
        }
        foreach ($duplicates as $md5 => $files) {
            if (count($files) === 1) {
                unset($duplicates[$md5]);
            }
        }
        $this->images = $duplicates;
        echo "<pre>";
        print_r($this->images);
        echo "</pre>";
    }
}
