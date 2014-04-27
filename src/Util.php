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

    /**
     * Overcome missing timezone issue for dates.
     *
     * @param string $string
     * @param string $format
     *
     * @return string
     */
    final public static function formatDate($string, $format = 'Y-m-d')
    {
        $date = new \DateTime($string, new \DateTimeZone('UTC'));

        return $date->format($format);
    }

    /**
     * @param array        $item
     * @param string|array $prefixes
     *
     * @return array
     */
    final public static function groupParameters(array $item, $prefixes)
    {
        foreach ((array) $prefixes as $prefix) {
            $keys = array_filter(array_keys($item), function ($key) use ($prefix) {
                return strpos($key, $prefix) === 0;
            });

            foreach ($keys as $key) {
                $item[$prefix][substr($key, strlen($prefix) + 1)] = $item[$key];
                unset($item[$key]);
            }
        }

        return $item;
    }

    /**
     * Combine retrieved pictures information with images.
     *
     * @param array $item
     *
     * @return array
     */
    final public static function mergePicturesWithImages(array $item)
    {
        if (!empty($item['pictures'])) {
            foreach ($item['pictures'] as $picture) {
                if (!empty($picture['url']) && !empty($picture['type'])) {
                    $item['images'][$picture['type']] = $picture['url'];
                }
            }
            unset($item['pictures']);
        }

        return $item;
    }

    /**
     * Make image format names more readable.
     *
     * @param array $item
     *
     * @return array
     */
    final public static function renameImageFormats(array $item)
    {
        if (!empty($item['images'])) {
            $images = [];
            foreach ($item['images'] as $key => $url) {
                $images[strtr(strtolower($key), ['event' => '', 'image' => '_'])] = $url;
            }
            $item['images'] = $images;
        }

        return $item;
    }
}
