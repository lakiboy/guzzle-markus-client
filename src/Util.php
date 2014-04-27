<?php

namespace Devmachine\Guzzle\Markus;

use GuzzleHttp\Command\Guzzle\SchemaFormatter;

final class Util
{
    private static $formatter;

    /**
     * @param array  $array
     * @param string $key
     *
     * @return mixed
     */
    final public static function getArrayElement($array, $key)
    {
        return isset($array[$key]) ? $array[$key] : null;
    }

    /**
     * Overcome missing timezone issue for dates.
     *
     * @param string $string
     *
     * @return string
     */
    final public static function formatDate($string)
    {
        return self::getFormatter()->format('date', new \DateTime($string, new \DateTimeZone('UTC')));
    }

    /**
     * @return SchemaFormatter
     */
    private static function getFormatter()
    {
        if (self::$formatter === null) {
            self::$formatter = new SchemaFormatter();
        }

        return self::$formatter;
    }
}
