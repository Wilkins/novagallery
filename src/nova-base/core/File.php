<?php

class File
{
    public const COVER = '.COVER.JPG';

    public const TRASH_DIR = 'Corbeille';

    public const EADIR = '@eaDir';

    public const DSSTORE = '.DS_Store';

    public const DSSTORE2 = '._.DS_Store';

    public const THUMBS = 'Thumbs.db';

    public const RENAME_ARCHIVE = 'rename_archive.txt';

    private const MONTH_DIRS = [
        '01' => '01.JANVIER',
        '02' => '02.FEVRIER',
        '03' => '03.MARS',
        '04' => '04.AVRIL',
        '05' => '05.MAI',
        '06' => '06.JUIN',
        '07' => '07.JUILLET',
        '08' => '08.AOUT',
        '09' => '09.SEPTEMBRE',
        '10' => '10.OCTOBRE',
        '11' => '11.NOVEMBRE',
        '12' => '12.DECEMBRE',
    ];

    private const SPECIAL_WORK_DIRS = [
        'UGAP',
        'OpenClassrooms',
        'HOURA',
        'UNGI',
        'CELESTE',
    ];

    private const SPECIAL_GROUP_DIRS = [
        'BestOf',
        'Maison',
        'Snapchat',
        'Divers',
    ];

    private const SPECIAL_DIRS = [
        'a_trier',
    ];

    public static function isSpecialDir(string $elementLink): bool
    {
        return in_array($elementLink, array_merge(
            self::SPECIAL_WORK_DIRS,
            self::SPECIAL_GROUP_DIRS,
            self::SPECIAL_DIRS,
        ), true);
    }


    public static function isMonthDir(string $elementLink): bool
    {
        return in_array($elementLink, self::MONTH_DIRS, true);

    }

    public static function download(string $relativeName): void
    {
        $okFile = Synology::getFullFilename($relativeName);
        if (file_exists($okFile)) {
            $cleanFile = self::cleanDownloadName($relativeName);
            //$mime = mime_content_type($okFile);
            header('Content-Type: application/octet-stream');

            header('Content-Description: File Transfer');
            header("Content-Disposition: attachment; filename=\"$cleanFile\"");
            header("Content-Length: " . filesize($okFile));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            readfile($okFile);
            //exit;
        } else {
            throw new Exception("Fichier introuvable");
        }
    }

    private static function cleanDownloadName(string $okFile)
    {
        $okFile = str_replace(array_values(self::MONTH_DIRS), array_keys(self::MONTH_DIRS), $okFile);
        return preg_replace('#^(\d\d\d\d)/(\d\d)/.*/(\w+.\w+)$#', '$1_$2_$3', $okFile);
    }

    public static function special(string $string): string
    {
        return THEME_PATH . "/assets/specials/$string.png";
    }

    public static function getSpecialWorkActions(): array
    {
        return self::getSpecialActions(self::SPECIAL_WORK_DIRS);
    }

    public static function getSpecialGroupActions(): array
    {
        return self::getSpecialActions(self::SPECIAL_GROUP_DIRS);
    }

    private static function getSpecialActions(array $dirs): array
    {
        $actions = [];
        foreach ($dirs as $dir) {
            $actions[] = [
                'name' => $dir,
                'url' => 'moveto-'.strtolower($dir),
                'icon' => self::special($dir),
            ];
        }
        return $actions;
    }
}
