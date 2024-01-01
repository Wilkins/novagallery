<?php

class Mode
{
    public const MODE_EDITION = 'edition';
    public const MODE_NAVIGATION = 'navigation';

    public const LIST = [
        self::MODE_EDITION,
        self::MODE_NAVIGATION,
    ];
    public const DEFAULT = self::MODE_NAVIGATION;

    private const KEY = 'mode';

    public static function set($mode): void
    {
        if (in_array($mode, self::LIST, true) === false) {
            throw new Exception('Mode not allowed');
        }

        $_SESSION[self::KEY] = $mode;
    }

    public static function get(): string
    {
        return $_SESSION[self::KEY] ?? self::DEFAULT;
    }

    public static function isEdition(): bool
    {
        return self::get() === self::MODE_EDITION;
    }

    public static function isNavigation(): bool
    {
        return self::get() === self::MODE_NAVIGATION;
    }
}
