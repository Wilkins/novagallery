<?php

class GalleryDuplicate extends Gallery
{
    private const MD5_SEPARATOR = '  ';

    protected function listAlbums(): void
    {
        $this->albums = $this->fileList([]);
    }

    protected function processImages(): void
    {
        ini_set('max_execution_time', 0);

        $this->images = $this->listDuplicates();

        $rw = $_GET['delete'] ?? false;
        if ($rw) {
            $this->processDelete($this->images);
        }
        $this->images = $this->listDuplicates();
    }

    private function listDuplicates(): array
    {
        $dirs = explode('/', $this->album);
        $md5file = implode('/', [$this->root, $dirs[0]]).'.md5sum';
        $lines = file($md5file);
        $duplicates = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (!$line) {
                continue;
            }
            [$md5, $file] = explode(self::MD5_SEPARATOR, $line, 2);
            if (!preg_match("#^".$this->album."#", $file)) {
                continue;
            }
            if (!isset($duplicates[$md5])) {
                $duplicates[$md5] = [];
            }
            $duplicates[$md5][] = $file;
        }
        foreach ($duplicates as $md5 => $files) {
            if (count($files) > 1) {
                $files = array_filter($files, [$this, 'filterMissing']);
                $duplicates[$md5] = array_values($files);
            }
            if (count($files) <= 1) {
                unset($duplicates[$md5]);
            }
        }
        $duplicates = array_slice($duplicates, -100);
        return $duplicates;
    }
    public function filterMissing($file): bool
    {
        return file_exists(IMAGES_DIR.'/'.$file);
    }

    private function processDelete($duplicates): void
    {
        $nbRemoved = 0;
        foreach ($duplicates as $md5 => $files) {
            if ($this->filesCanBeDeleted($files[0], $files[1])) {
                $deleteFile = $this->compareFiles($files[0], $files[1]);
                $nbRemoved++;
                /*
                echo "<pre>";
                echo "files0 : ".$files[0]. "\n";
                echo "files1 : ".$files[1]. "\n";
                echo "remove $deleteFile\n";
                echo "</pre>";
                */
                Metadata::toggleTrashFromUrl($deleteFile);
            }
        }
        echo "<p class=\"danger\">$nbRemoved doublons supprimé(s)</p>\n";
    }

    private function compareFiles($file0, $file1)
    {
        if (false !== stripos($file0, "trier")) {
            return $file0;
        }
        if (false !== stripos($file1, "trier")) {
            return $file1;
        }
        if (false !== stripos($file0, "brut")) {
            return $file0;
        }
        if (false !== stripos($file1, "brut")) {
            return $file1;
        }
        if (false !== stripos($file0, "garder")) {
            return $file1;
        }
        if (false !== stripos($file1, "garder")) {
            return $file0;
        }
        if (false !== stripos($file0, "selection")) {
            return $file1;
        }
        if (false !== stripos($file1, "selection")) {
            return $file0;
        }
        $nbSlash0 = substr_count($file0, '/');
        $nbSlash1 = substr_count($file1, '/');
        if ($nbSlash0 > $nbSlash1) {
            return $file1;
        } elseif ($nbSlash0 < $nbSlash1) {
            return $file0;
        } else {
            if (strlen(basename($file0)) < strlen(basename($file1))) {
                return $file1;
            }
            return $file0;
        }
    }

    private function filesCanBeDeleted($file0, $file1): bool
    {
        if ($file0 === $file1) {
            // It's the same file, we don't want to delete it
            throw new Exception("Both files are the same files : $file0 <==> $file1");
        }
        if (!file_exists(IMAGES_DIR.'/'.$file0)) {
            echo "file0 not exists $file0<br>\n";
            return false;
        }
        if (!file_exists(IMAGES_DIR.'/'.$file1)) {
            echo "file1 not exists $file1<br>\n";
            return false;
        }

        $md50 = FileSystem::md5(IMAGES_DIR.'/'.$file0);
        $md51 = FileSystem::md5(IMAGES_DIR.'/'.$file1);
        return $md50 === $md51;
    }

    public function parentAlbum($album): string
    {
        return $album;
    }
}
