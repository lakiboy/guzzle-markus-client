<?php

namespace Devmachine\Tests\Guzzle\Markus;

use Devmachine\Guzzle\Markus\MarkusClient;
use Devmachine\Guzzle\Markus\MarkusDescription;
use GuzzleHttp\Adapter\MockAdapter;
use GuzzleHttp\Adapter\TransactionInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class MarkusClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var MarkusClient */
    private $client;

    /** @var MockAdapter */
    private $mock;

    public function setUp()
    {
        $description = new MarkusDescription('http://forumcinemas.lv/xml');
        $this->mock = new MockAdapter();
        $this->client = new MarkusClient(new Client(['adapter' => $this->mock]), $description);
    }

    public function testAreas()
    {
        $result = $this->getClient('areas')->areas();

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(2, $result['items']);

        $this->assertEquals(1, $result['items'][0]['id']);
        $this->assertEquals('Markus Cinema One', $result['items'][0]['name']);

        $this->assertEquals(2, $result['items'][1]['id']);
        $this->assertEquals('Markus Cinema Two', $result['items'][1]['name']);
    }

    public function testLanguages()
    {
        $result = $this->getClient('languages')->languages();

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(3, $result['items']);

        $this->assertEquals(2, $result['items'][1]['id']);
        $this->assertEquals('Russian', $result['items'][1]['name']);
        $this->assertEquals('Krievu', $result['items'][1]['local_name']);
        $this->assertEquals('Krievu valodā', $result['items'][1]['original_name']);
        $this->assertEquals('ru', $result['items'][1]['code']);
        $this->assertEquals('rus', $result['items'][1]['three_letter_code']);
    }

    public function testSchedule()
    {
        $this->mock->setResponse(function (TransactionInterface $transaction) {
            $query = $transaction->getRequest()->getQuery();

            if ($query->hasKey('area')) {
                return $this->createResponse('schedule_' . $query->get('area'));
            }

            return $this->createResponse('schedule');
        });

        // Returns result with 2 items.
        $result = $this->client->schedule(array('area' => 1000));

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(2, $result['items']);
        $this->assertEquals('2014-05-01T00:00:00', $result['items'][0]);
        $this->assertEquals('2014-05-10T00:00:00', $result['items'][1]);

        // Returns result with 7 items.
        $result = $this->client->schedule();

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(7, $result['items']);
    }

    public function testArticleCategories()
    {
        $result = $this->getClient('article_categories')->articleCategories();

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(3, $result['items']);

        $this->assertEquals(1005, $result['items'][0]['id']);
        $this->assertEquals('Filmu ziņas', $result['items'][0]['name']);
        $this->assertEquals(10, $result['items'][0]['article_count']);
    }

    /**
     * Set mock response from file.
     *
     * @param string $fixture
     *
     * @return \Devmachine\Guzzle\Markus\MarkusClient
     */
    private function getClient($fixture)
    {
        $this->mock->setResponse($this->createResponse($fixture));

        return $this->client;
    }

    private function createResponse($fixture)
    {
        $responseXml = __DIR__ . '/fixtures/' . $fixture . '.xml';

        return new Response(200, [], Stream::factory(file_get_contents($responseXml)));
    }
}
