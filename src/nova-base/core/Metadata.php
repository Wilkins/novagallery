<?php


class Metadata
{
    public const METADATA = '.METADATA.JSON';

    public const ALL_KEYS = [
        self::FAVORITES_KEY,
        self::DURATION_KEY,
    ];

    public const FAVORITES_KEY = 'favorites';

    public const DURATION_KEY = 'duration';

    public const TRASH_KEY = 'trash';

    public const FILETYPE_KEY = 'filetype';

    public const FULLNAME_KEY = 'fullname';

    public static function toggleFavoriteFromUrl(string $fullFilename): void
    {
        $imageName = basename($fullFilename);
        $metadata = self::getAll($fullFilename);
        if (isset($metadata[self::FAVORITES_KEY][$imageName])) {
            unset($metadata[self::FAVORITES_KEY][$imageName]);
        } else {
            $metadata[self::FAVORITES_KEY][$imageName] = 1;
        }
        self::saveAll(self::getMetadataFilename($fullFilename), $metadata);
    }

    public static function saveVideoDuration(string $fullFilename): void
    {
        $imageName = basename($fullFilename);
        $metadata = self::getAll($fullFilename);
        if (isset($metadata[self::FAVORITES_KEY][$imageName])) {
            unset($metadata[self::FAVORITES_KEY][$imageName]);
        } else {
            $metadata[self::FAVORITES_KEY][$imageName] = 1;
        }
        self::saveAll(self::getMetadataFilename($fullFilename), $metadata);
    }

    private static function getMetadataFilename(string $fullFilename): string
    {
        $dirName = Synology::isAlbum($fullFilename) ? $fullFilename : dirname($fullFilename);
        return Synology::getFullFilename($dirName . '/' . self::METADATA);
    }

    /**
     * @return array|string
     */
    public static function getKey(string $fullFilename, string $key = null): array
    {
        $allKeys = self::getAll($fullFilename);

        if (array_key_exists($key, $allKeys)) {
            return $allKeys[$key];
        }
        return [];
    }

    public static function getAll(string $fullFilename): array
    {
        //echo "getMetadata($fullFilename)<br>\n";
        $metadataFile = self::getMetadataFilename($fullFilename);

        //echo "getAll metadataFile : ".$metadataFile . "<br>\n";
        if (!file_exists($metadataFile)) {

            if (!is_writable(dirname($metadataFile))) {
                //throw new Exception("Le répertoire « " . dirname($metadataFile) . " » n'est pas accessible en écriture");
                return [];
            }
            self::createEmpty($metadataFile);
        }
        $metadata = json_decode(file_get_contents($metadataFile), true, 512, JSON_THROW_ON_ERROR);
        /*
        print "JSON decode<br>\n";
        print_r($metadata);
        print "<br>\n";
        */

        return $metadata;
    }

    private static function createEmpty(string $metadataFile): void
    {
        $emptyData = [];
        foreach (self::ALL_KEYS as $key) {
            $emptyData[$key] = [];
        }
        self::saveAll($metadataFile, $emptyData);
    }

    public static function saveKey(string $albumName, string $key, array $data): void
    {
        //echo "<br>";
        //echo "saveKey $albumName $key<br>\n";
        $metadataFile = self::getMetadataFilename($albumName);
        $metadata = self::getAll($albumName);
        $metadata[$key] = $data;
        //echo "saveKey metadataFile : ".$metadataFile . "<br>\n";
        //print_R($metadata);
        self::saveAll($metadataFile, $metadata);
        //echo "END saveKey<br>\n";
    }

    private static function saveAll(string $metadataFile, array $data): void
    {
        file_put_contents($metadataFile, json_encode($data, JSON_THROW_ON_ERROR));
    }

    public static function toggleTrashFromUrl(string $fullFilename): void
    {
        $okFile = Synology::getFullFilename($fullFilename);
        $trashFile = Synology::getFullFilename(File::TRASH_DIR . '/' . $fullFilename);
        if (file_exists($okFile) && !file_exists($trashFile)) {
            FileSystem::moveFile($okFile, $trashFile);
        } else if (!file_exists($okFile) && file_exists($trashFile)) {
            FileSystem::moveFile($trashFile, $okFile);
        } else if (file_exists($okFile) && file_exists($trashFile)) {
            throw new Exception("Fichier déjà dans la corbeille");
        } else {
            throw new Exception("Fichier introuvable");
        }
    }
}
