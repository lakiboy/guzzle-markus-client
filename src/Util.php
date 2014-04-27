<?php

namespace Devmachine\Guzzle\Markus;

final class Util
{
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
}
