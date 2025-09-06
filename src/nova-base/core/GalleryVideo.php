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

    public static function getVideo($video): string
    {
        $url = IMAGES_URL_CODE.'/'.$video;
        $file = Album::getFullFilename($video);
        $possibles = [
            'SYNOPHOTO_FILM_H264.mp4',
            'SYNOPHOTO_FILM_M.mov',
            'SYNOPHOTO_FILM_M.mp4',
        ];
        foreach ($possibles as $possible) {
            if (is_file(dirname($file).'/'.File::EADIR.'/'.basename($file).'/'.$possible)) {
                return dirname($url).'/'.File::EADIR.'/'.basename($file).'/'.$possible;
            }
        }
        return dirname($url).'/'.File::EADIR.'/'.basename($file).'/SYNOPHOTO_FILM_M.mp4';
    }

    public static function getVideo2($video): string
    {
        return self::getVideo($video);
    }
}
