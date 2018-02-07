<?php

namespace MMollick\Drip;

class Utils
{
    protected static $version;

    /**
     * Returns wrapper version
     * @return string
     */
    public static function wrapperVersion()
    {
        if (!self::$version) {
            self::$version = file_get_contents(__DIR__ . '/../VERSION');
        }

        return self::$version;
    }
}
