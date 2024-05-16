<?php

class Exif
{
    public static function getInfo($image): array
    {
        $okFile = Album::getFullFilename($image);
        $exif = exif_read_data($okFile, 0, true);
        return [
            'Nom' => basename($image),
            'Taille' => $exif['COMPUTED']['Width'] . ' x ' . $exif['COMPUTED']['Height'],
            'Volume' => number_format($exif['FILE']['FileSize'] / 1024, 0, ".", " " ) . ' Ko',
            'Date' => preg_replace('/(\d{4}):(\d{2}):(\d{2}) (\d{2}):(\d{2}):(\d{2})/', '$3/$2/$1 $4:$5:$6', $exif['EXIF']['DateTimeOriginal'] ?? ""),
            'Appareil' => ($exif['IFD0']['Make'] ?? "")." ".($exif['IFD0']['Model'] ?? ""),
            'Format' => $exif['FILE']['MimeType'],
            'Commentaire' => self::getComment($image),
        ];
    }

    public static function saveComment($image, $comment): void
    {
        $commentFile = self::getCommentFile($image);
        $commentFullFilename = Album::getFullFilename($commentFile);
        file_put_contents($commentFullFilename, $comment);
    }

    public static function getCommentFile($image): string
    {
        return  preg_replace('#\.[a-zA-Z]+$#', '.txt', $image);
    }

    public static function getComment($image): string
    {
        $commentFile = self::getCommentFile($image);
        if (!Album::fileExists($commentFile)) {
            return "";
        }
        $commentFullFilename = Album::getFullFilename($commentFile);
        $content = file_get_contents($commentFullFilename);
        $encodings = ['UTF-8', 'ISO-8859-1', 'ASCII'];
        return iconv(mb_detect_encoding($content, $encodings, true), "UTF-8", $content);
    }
}
