<?php

namespace Devmachine\Guzzle\Markus;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Model;

/**
 * @method Model areas()
 * @method Model languages()
 * @method Model schedule()
 * @method Model articleCategories()
 * @method Model articles()
 * @method Model events()
 * @method Model shows()
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
