<?php

class Exif
{

    public static function getInfo($image): array
    {
        $okFile = IMAGES_DIR . '/' . $image;
        $exif = exif_read_data($okFile, 0, true);
        return [
            'Nom' => basename($image),
            'Taille' => $exif['COMPUTED']['Width'] . ' x ' . $exif['COMPUTED']['Height'],
            'Volume' => number_format($exif['FILE']['FileSize'] / 1024, 0, ".", " " ) . ' Ko',
            'Date' => preg_replace('/(\d{4}):(\d{2}):(\d{2}) (\d{2}):(\d{2}):(\d{2})/', '$3/$2/$1 $4:$5:$6', $exif['EXIF']['DateTimeOriginal'] ?? ""),
            'Appareil' => ($exif['IFD0']['Make'] ?? "")." ".($exif['IFD0']['Model'] ?? ""),
            'Format' => $exif['FILE']['MimeType'],
        ];
    }

    public static function getComment($image): string
    {
        $commentFile = preg_replace('#\.[a-zA-Z]$#', '.txt', $image);
        $okFile = Synology::getFullFilename($image);
        $exif = exif_read_data($okFile, 0, true);
        return $exif['IFD0']['ImageDescription'] ?? "";
    }

    public static function getHtmlInfo($image): string
    {
        $infos = self::getInfo($image);
        $html = "<table cellpadding='3' border='1'>\n";
        foreach ($infos as $info => $value) {
            $html .= "<tr><th style='text-align: left'>$info</th><td style='text-align: left'>$value</td></tr>\n";
        }
        $html .= "</table>\n";
        return $html;
    }
}
