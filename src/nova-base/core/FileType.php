<?php

class FileType
{
    private const VIDEO_FORMATS = [
        'MOV',
        'MP4',
        'MPEG4',
        'MPG',
        'AVI',
        'MTS',
        'WEBM',
        'MKV',
        'VOB',
        'AVIF',
    ];
    private const SOUND_FORMATS = [
        'M4A',
        'MP3',
        'WAV',
    ];
    private const OTHER_FORMATS = [
        'ISO',
        'PPT',
    ];
    private const IMAGE_FORMATS = [
        'JPG',
        'JPEG',
        'PNG',
        'GIF',
        'WEBP',
    ];
    private const ALL_FORMATS = [
        self::VIDEO_FORMATS,
        self::IMAGE_FORMATS,
        self::SOUND_FORMATS,
        self::OTHER_FORMATS,
    ];

    public static function getAcceptedFormats(): string
    {
        $allFormats = array_merge(...self::ALL_FORMATS);
        $formats = array_merge(
            $allFormats,
            array_map('strtolower', $allFormats),
        );
        return '{' . implode(',', $formats) . '}';
    }


    public static function isVideo($element): bool
    {
        return preg_match('/\.(' . implode('|', self::VIDEO_FORMATS) . ')$/i', $element) === 1;
    }
}