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
     * @param array $items
     *
     * @return array
     */
    final public static function renameImageFormats(array $items)
    {
        $result = [];
        foreach ($items as $key => $url) {
            $result[self::renameImageFormat($key)] = $url;
        }

        return $result;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    final public static function renameImageFormat($format)
    {
        return strtr(strtolower($format), ['event' => '', 'image' => '_']);
    }

    /**
     * This methods calculates show end time in UTC.
     *
     * For some reason API provides show start/end time in local timezone and show start time in UTC.
     * Show end time in UTC in all API examples is incorrect.
     *
     * @param array $item
     *
     * @return array
     */
    final public static function fixShowEndTimeUTC(array $item)
    {
        $startTime = new \DateTime($item['start_time']);
        $endTime = new \DateTime($item['end_time']);

        $startTimeUtc = new \DateTime($item['start_time_utc'], new \DateTimeZone('UTC'));
        $endTimeUtc = $startTimeUtc->add($startTime->diff($endTime));

        $item['end_time_utc'] = $endTimeUtc->format('Y-m-d\TH:i:s\Z');

        return $item;
    }
}
