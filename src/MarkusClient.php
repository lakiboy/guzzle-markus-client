<?php

namespace Devmachine\Guzzle\Markus;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

/**
 * @method array areas()
 * @method array languages()
 * @method array schedule()
 * @method array articleCategories()
 * @method array articles()
 * @method array events()
 * @method array shows()
 */
class MarkusClient extends GuzzleClient
{
    /**
     * @param string $baseUrl
     * @param array  $options
     *
     * @return MarkusClient
     */
    public static function factory($baseUrl, array $options = [])
    {
        return new static(new Client(), new MarkusDescription($baseUrl), $options);
    }
}
