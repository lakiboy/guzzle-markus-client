<?php

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
namespace Devmachine\Guzzle\Markus;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

/**
 * @method \GuzzleHttp\Command\Model areas()
 * @method \GuzzleHttp\Command\Model languages()
 * @method \GuzzleHttp\Command\Model schedule()
 * @method \GuzzleHttp\Command\Model articleCategories()
 * @method \GuzzleHttp\Command\Model articles()
 * @method \GuzzleHttp\Command\Model events()
 * @method \GuzzleHttp\Command\Model shows()
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
