<?php

/**
 * Language Object
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @link https://novagallery.org
 **/
class Lang
{

    private static $translations;

    public static function initialize($language)
    {
        self::$translations = false;
        if (file_exists(ROOT_DIR . '/nova-languages/' . $language . '.php')) {
            self::$translations = JsonDB::read(ROOT_DIR . '/nova-languages/' . $language . '.php');
        }
    }

    public static function get($string)
    {
        if (!self::$translations) {
            return $string;
        }

        $key = strtolower($string);
        $key = str_replace(array(' ', '.', ',', '"'), array('-', '', '', ''), $key);

        return self::$translations->$key ?? $string;
    }

    public static function p($string)
    {
        echo self::get($string);
    }
}
