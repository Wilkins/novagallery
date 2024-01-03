<?php

class FileType
{
    public const READABLE_VIDEO_FORMATS = [
        'MOV',
        'M4V',
    ];

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
        'WMV',
        'M4V',
        'OGG',
        'MOD',
        '3GP',
    ];
    private const SOUND_FORMATS = [
        'M4A',
        'MP3',
        'WAV',
    ];
    private const OTHER_FORMATS = [
        'ISO',
        'PPT',
        'PPS',
        'PPSX',
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

    public static function getVideoFormats(): array
    {
        return self::VIDEO_FORMATS;
    }
}
