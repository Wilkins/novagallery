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
class Gallery
{

    protected $dir = '';
    protected $album = '';
    protected $root = '';
    protected $trashDir = '';
    protected $images = [];
    protected $albums = [];
    private const DEBUG = false;

    public function __construct($root, $album)
    {
        $this->root = $root;
        $this->album = $album;
        $this->dir = rtrim($root . '/' . $album, '/');
        $this->trashDir = rtrim($root . '/' . File::TRASH_DIR . '/' . $album, '/');
        $this->listAlbums();
        $this->processImages();
    }

    protected function listAlbums(): void
    {
        $start = microtime(true);
        $fileSystem = new FileSystem($this->dir);
        $dirs = $fileSystem->listDirectories();
        if (self::DEBUG) {
            echo "core\FileSystem::listDirectories($this->dir/*) (" . (microtime(true) - $start) . " sec)<br>\n";
            echo "<pre>";
            print_R($dirs);
            echo "</pre>";
        }
        /*
        $start = microtime(true);
        $dirs = core\lib\core\FileSystem::listDirectories2($this->dir);
        echo "core\lib\core\FileSystem::listDirectories2($this->dir/*}) (".(microtime(true)-$start)." sec)<br>\n";
        echo "<pre>";
        print_R($dirs);
        echo "</pre>";
  */
        /*
        */
        $start = microtime(true);
        //$dirs = glob($this->dir . '/' . "*", GLOB_ONLYDIR);
        /*
        if (self::DEBUG) {
            echo "glob($this->dir/*) (" . (microtime(true) - $start) . " sec)<br>\n";
            echo "<pre>";
            print_R($dirs);
            echo "</pre>";
        }
        */


        $this->albums = $this->fileList($dirs);
        unset($this->albums["@eaDir"]);

        //$this->albums = $this->fileList($dirs);
        //unset($this->albums["@eaDir"]);
    }

    private function sortByDate(array $files): array
    {

        $filesWithTimes = [];
        foreach ($files as $file) {
            $filesWithTimes[filemtime($file)] = $file;
        }
        ksort($filesWithTimes);
        return array_values($filesWithTimes);

    }

    protected function processImages(): void
    {
        $start = microtime(true);
        $imagesOk = $this->sortByDate(glob($this->dir . '/*.' . FileType::getAcceptedFormats(), GLOB_BRACE));
        $imagesTrash = glob($this->trashDir . '/*.' . FileType::getAcceptedFormats(), GLOB_BRACE);
        $images = array_merge($imagesOk, $imagesTrash);
        if (self::DEBUG) {
            echo "processImages<br>\n";
            echo "glob($this->dir/*{jpg,jpeg,JPG,JPEG,png,PNG}) (" . (microtime(true) - $start) . " sec)<br>\n";
        }
        $this->images = $this->fileList($images, false);
    }

    // create array of files or dirs without path & with last modification date
    protected function fileList($list, $withCaptureDate = false): array
    {
        $fileList = [];
        foreach ($list as $element) {
            if ($withCaptureDate) { // add modification date if requested
                $value = $this->getImageCaptureDate($element);
            } else {
                $value = [
                    Metadata::TRASH_KEY => preg_match("#" . Album::getFullFilename(File::TRASH_DIR) . "#", $element) ? 1 : 0,
                    Metadata::FULLNAME_KEY => $this->getRelativePath($element),
                ];
                if (FileType::isVideo($element)) {
                    $value[Metadata::FILETYPE_KEY] = 'video';
                    $value[Metadata::DURATION_KEY] = $this->getVideoDurationWithCache($element);
                } else {
                    $value[Metadata::FILETYPE_KEY] = 'image';
                }
            }
            $element = strrchr($element, '/');
            $element = substr($element, 1);
            if ($value[Metadata::TRASH_KEY] === 0) {
                $fileList[$element] = $value;
            }
        }
        return $fileList;
    }

    protected function getImageCaptureDate($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        if (preg_match('/\.(JPEG|jpeg|JPG|jpg|png|PNG)$/', $file) === 0) {
            return filemtime($file); // use filetime, if no image
        }

        // Get the photo's EXIF tags
        try {
            @$exif_data = exif_read_data($file);
            if ($exif_data === false) {
                return filemtime($file); // use filemtime, if no exif data
            }
        } catch (Exception $e) {
            return filemtime($file); // use filemtime, if exif data error
        }


        // default value, which represents no date
        $date = false;
        // Array of EXIF date tags to check
        $date_tags = [
            'DateTimeOriginal',
            'DateTimeDigitized',
            'DateTime',
            //'FileDateTime'
        ];

        // Check for the EXIF date tags, in the order specified above. First value wins.
        foreach ($date_tags as $date_tag) {
            if (isset($exif_data[$date_tag])) {
                $date = $exif_data[$date_tag];
                $date = $this->timestampFromExif($date);
                break;
            }
        }

        // If no date tags were found use filemtime
        if (!$date) {
            return filemtime($file);
        }

        //If the date that was extracted is a string, convert it to an integer
        if (is_string($date)) $date = strtotime($date);

        return $date;
    }

    protected function timestampFromExif($string): string
    {
        if (!(preg_match('/\d\d\d\d:\d\d:\d\d \d\d:\d\d:\d\d/', $string))) {
            return $string; // wrong date
        }

        $iTimestamp = mktime(
            substr($string, 11, 2),
            substr($string, 14, 2),
            substr($string, 17, 2),
            substr($string, 5, 2),
            substr($string, 8, 2),
            substr($string, 0, 4));
        return $iTimestamp;
    }

    protected function shuffle_assoc($array): array
    {
        $keys = array_keys($array);
        shuffle($keys);

        foreach ($keys as $key) {
            $new[$key] = $array[$key];
        }

        return $new;
    }

    protected function order($list, $order): array
    {
        return $list;
        switch ($order) {
            case 'oldest':
                asort($list);
                break;
            case 'newest':
                arsort($list);
                break;
            case 'random':
                $list = $this->shuffle_assoc($list);
                break;
            default:
                // order by name
                $list = $this->orderByName($list);
                break;
        }
        return $list;
    }

    // sort array by natcasesort with german umlaute
    // solution based on http://www.marcokrings.de/arrays-sortieren-mit-umlauten/
    protected function orderByName($list)
    {
        // swap key (name) value (timestamp) for order operations
        $nameList = [];
        foreach ($list as $album => $value) {
            array_push($nameList, $album);
        }

        // sort based on http://www.marcokrings.de/arrays-sortieren-mit-umlauten/
        $aOriginal = $nameList;
        if (count($aOriginal) == 0) {
            return $aOriginal;
        }
        $aModified = [];
        $aReturn = [];
        $aSearch = array("Ä", "ä", "Ö", "ö", "Ü", "ü", "ß", "-");
        $aReplace = array("A", "a", "O", "o", "U", "u", "ss", " ");
        foreach ($aOriginal as $key => $val) {
            $aModified[$key] = str_replace($aSearch, $aReplace, $val);
        }
        natcasesort($aModified);
        foreach ($aModified as $key => $val) {
            $aReturn[$key] = $aOriginal[$key];
        }

        // swap back to have a orderd list with the correct key (album) value (timestamp) format
        $orderedList = [];
        foreach ($aReturn as $value) {
            $orderedList[$value] = $list[$value];

        }

        return $orderedList;
    }

    public function getAlbums($order = 'default'): array
    {
        // order images in albums
        $orderedImages = [];
        foreach ($this->albums as $album => $images) {
            $orderedImages[$album] = $this->order($images, $order);
        }

        // order albums based on first image
        $orderedAlbums = [];
        // create array with albums and timestamp of first image
        foreach ($orderedImages as $album => $images) {
            if (!empty($images)) {
                $orderedAlbums[$album] = array_values($images)[0];
            } else {
                $orderedAlbums[$album] = '';
            }
        }
        $orderedAlbums = $this->order($orderedAlbums, $order);
        // create array with all albums and all images orderd
        $albums = [];
        foreach ($orderedAlbums as $album => $value) {
            $albums[$album] = $orderedImages[$album];
        }

        return $albums;
    }

    public function images($order = 'default'): array
    {
        return $this->order($this->images, $order);
    }

    public function coverImage($album, $order = 'default'): string
    {
        return IMAGES_URL . "/$album/" . File::COVER;
    }

    public function hasCoverImage($album, $order = 'default'): string
    {
        return file_exists(Album::getFullFilename("/$album/" . File::COVER));
    }

    private function getRelativePath($file): string {
        return str_replace($this->dir.'/', '', $file);
    }

    public function hasAlbums(): bool
    {
        return !empty($this->albums);
    }

    public function isDeletable(): bool
    {
        return count($this->albums) === 0 && count($this->images) === 0;
    }

    public function isWritable(): bool
    {
        return is_writable($this->dir);
    }

    public function hasImages($album = false): bool
    {
        // choose correct image array
        if ($album) {
            $imageList = &$this->albums[$album];
        } else {
            $imageList = &$this->images;
        }

        return !empty($imageList);
    }

    public function parentAlbum($album): string
    {
        $parent = strrpos($album, '/');
        return ltrim(substr($album, 0, $parent), '/');
    }

    private function getVideoDurationWithCache($element): string
    {
        $albumName = str_replace(IMAGES_DIR . '/', '', dirname($element));
        $imageName = basename($element);
        $durations = Metadata::getKey($albumName, Metadata::DURATION_KEY);
        if (isset($durations[$imageName])) {
            return $durations[$imageName];
        }
        $duration = $this->getVideoDuration($element);
        $durations[$imageName] = $duration;
        //echo "Durations to save<br>\n";
        //print_r($durations);
        Metadata::saveKey($albumName, Metadata::DURATION_KEY, $durations);

        return $duration;
    }

    private function getVideoDuration($element): string
    {
        $totalSeconds = trim(shell_exec('/usr/local/bin/ffprobe -v error '
            . ' -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '
            . ' "' . $element . '"'));
        if (!is_numeric($totalSeconds)) {
            return '00:00';
        }
        $minutes = floor($totalSeconds / 60);
        $seconds = round($totalSeconds) % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
