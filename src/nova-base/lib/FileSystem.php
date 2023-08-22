<?php

final class FileSystem
{
    protected const CACHE_DIR = '';

    protected const CACHE_FILE = '.cache_files.php';

    private const IGNORED_DIRECTORIES = ['.', '..', 'cache', 'Cache', '@eaDir', '.cache', '.cache_files.php'];

    private const ACCEPTED_EXTENSIONS = ['jpeg', 'jpg', 'png', 'mov'];

    private $dir;

    private $albums = [];

    private $images = [];

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function readDir(): array
    {
        $this->load();
        $content = $this->buildContent();
        return $content;
    }

    /*
        public function readDir2($dir): array
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
                    $fullDir = $dir . '/' . $file;
                    if (!is_dir($fullDir)) {
                        $directories[] = $fullDir;
                        continue;
                    }

                }
                closedir($dh);
            }
            sort($directories);
            return $directories;
        }

        public function listCachedDirectories($dir): array
        {
            $maxAge = Site::config('maxAge');
            $cacheFile = $dir . '/' . $this->$cacheDir . '/' . $this->$cacheFile;
            if (!file_exists($cacheFile)) {
                return $this->listDirectories2($dir);
            }
            $age = time() - filemtime($cacheFile);
            if ($age > $maxAge) {
                return $this->listDirectories2($dir);
            }

            $content = file($cacheFile);
            unset($content[0]); // Remove first security line (<?php die();)
            $content = implode($content); // Regenerate JSON
            $content = json_decode($content, true);
            $this->images = $content['images'];
            $this->albums = $content['albums'];
            return true;
        }

        */

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

    public function listDirContent(): void
    {
        $directory = new DirectoryIterator($this->dir);
        foreach ($directory as $fileInfo) {
            if ($fileInfo->isDir() && in_array($fileInfo->getFilename(), self::IGNORED_DIRECTORIES, true)) {
                continue;
            }
            if ($fileInfo->isDir()) {
                $this->albums[$fileInfo->getFilename()] = [];
                continue;
            }

            if ($this->extensionIsAccepted($fileInfo->getExtension())) {
                $this->images[$fileInfo->getFilename()] = [];
            }
        }
        $this->saveCache();
    }

    private function extensionIsAccepted(string $extension): bool
    {
        return in_array(strtolower($extension), self::ACCEPTED_EXTENSIONS, true);
    }

    private function saveCache(): void
    {
        $cacheDir = $this->dir . '/' . self::CACHE_DIR;
        if (!file_exists($cacheDir)) {
            mkdir(self::CACHE_DIR, 0777, true);
        }
        $cacheFile = $cacheDir . '/' . self::CACHE_FILE;
        $content = $this->buildContent();
        $content = json_encode($content, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $data = '<?php die(); ?>' . PHP_EOL;
        $data .= $content;
        file_put_contents($cacheFile, $data, LOCK_EX); // LOCK_EX flag prevents that anyone else is writing to the file at the same time
    }

    private function buildContent(): array
    {
        sort($this->albums);
        sort($this->images);
        return [
            'albums' => $this->albums,
            'images' => $this->images,
        ];
    }


    public function load(): void
    {
        $maxAge = Site::config('maxAge');
        $cacheFile = $this->dir . '/' . self::CACHE_DIR . '/' . self::CACHE_FILE;
        if (!file_exists($cacheFile)) {
            $this->listDirContent();
            return;
        }
        $age = time() - filemtime($cacheFile);
        if ($age > $maxAge) {
            $this->listDirContent();
            return;
        }

        $content = file($cacheFile);
        unset($content[0]); // Remove first security line (<?php die();)
        $content = implode($content); // Regenerate JSON
        $content = json_decode($content, true);
        $this->images = $content['images'];
        $this->albums = $content['albums'];
    }
}