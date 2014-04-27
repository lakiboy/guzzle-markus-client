<?php

namespace Devmachine\Tests\Guzzle\Markus;

use Devmachine\Guzzle\Markus\MarkusClient;
use Devmachine\Guzzle\Markus\MarkusDescription;
use GuzzleHttp\Adapter\MockAdapter;
use GuzzleHttp\Adapter\TransactionInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class MarkusClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var MarkusClient */
    private $client;

    /** @var MockAdapter */
    private $mock;

    /** @var History */
    private $history;

    public function setUp()
    {
        $description = new MarkusDescription('http://forumcinemas.lv/xml');
        $this->mock = new MockAdapter();
        $this->history = new History();
        $this->client = new MarkusClient(new Client(['adapter' => $this->mock]), $description);
        $this->client->getHttpClient()->getEmitter()->attach($this->history);
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
        $result = $this->client->schedule(['area' => 1000]);

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(2, $result['items']);
        $this->assertEquals('2014-05-01', $result['items'][0]);
        $this->assertEquals('2014-05-10', $result['items'][1]);

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

    public function testArticles()
    {
        $result = $this->getClient('articles')->articles();

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(3, $result['items']);

        $this->assertEquals('2014-03-26', $result['items'][2]['published']);
        $this->assertEquals('UUS EESTI MÄNGUFILM "RISTTUULES" SINU KINOS', $result['items'][2]['title']);
        $this->assertEquals('Alates tänasest ootame kõiki vaatama uut eesti mängufilmi RISTTUULES. Martti Helde lavastatud mängufilm räägib eestlasi tabanud kuritööst linastub alates 26.03 Tallinnas ja Tartus ning alates 27.03 Narvas.', $result['items'][2]['abstract']);
        $this->assertStringStartsWith('<p style="text-align: justify;">See on film, mis taastab visuaalselt igaveseks', $result['items'][2]['content']);
        $this->assertEquals(299564, $result['items'][2]['event']);
        $this->assertEquals('http://www.forumcinemas.ee/News/MovieNews/2014-03-26/1889/UUS-EESTI-MANGUFILM-RISTTUULES-SINU-KINOS/', $result['items'][2]['url']);
        $this->assertEquals('http://media.forumcinemas.ee/1000/news/1889/risttuules_poster_valge.png', $result['items'][2]['image_url']);
        $this->assertEquals('http://media.forumcinemas.ee/1000/news/1889/THUMB_risttuules_poster_valge.png', $result['items'][2]['thumbnail_url']);

        $this->assertCount(2, $result['items'][2]['categories']);
        $this->assertEquals(1005, $result['items'][2]['categories'][0]['id']);
        $this->assertEquals('Filmimaailm', $result['items'][2]['categories'][0]['name']);
        $this->assertEquals(1002, $result['items'][2]['categories'][1]['id']);
        $this->assertEquals('Kinoklubi', $result['items'][2]['categories'][1]['name']);
    }

    public function testArticlesWithArguments()
    {
        $this->getClient('articles')->articles([
            'area' => 1,
            'event' => 2,
            'category' => 3,
            'dummy' => 'any_value'
        ]);

        // Test query parameters where actually sent.
        $this->assertEquals(['area', 'eventID', 'categoryID'], $this->history->getLastRequest()->getQuery()->getKeys());
    }

    public function testEventsWithDefaultArguments()
    {
        $this->getClient('events')->events();

        $this->assertEquals([
            'includeVideos' => 'false',
            'includeLinks' => 'false',
            'includeGallery' => 'false',
            'includePictures' => 'false',
            'listType' => 'NowInTheatres'
        ], $this->history->getLastRequest()->getQuery()->toArray());
    }

    public function testUpcomingEvents()
    {
        $this->getClient('events')->events(['coming_soon' => true]);

        $this->assertTrue($this->history->getLastRequest()->getQuery()->hasKey('listType'));
        $this->assertEquals('ComingSoon', $this->history->getLastRequest()->getQuery()->get('listType'));
    }

    public function testEvents()
    {
        $result = $this->getClient('events')->events();

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(3, $result['items']);

        $this->assertEquals(301312, $result['items'][0]['id']);
        $this->assertEquals('3 dienas, lai nogalinātu', $result['items'][0]['title']);
        $this->assertEquals('3 Days to Kill', $result['items'][0]['original_title']);
        $this->assertEquals(2014, $result['items'][0]['year']);
        $this->assertEquals(113, $result['items'][0]['length']);
        $this->assertEquals('2014-04-18', $result['items'][0]['release_date']);
        $this->assertEquals('12+', $result['items'][0]['rating']['name']);
        $this->assertEquals('Līdz 12 g.v. -  neiesakām', $result['items'][0]['rating']['description']);
        $this->assertEquals('http://forumcinemaslv.blob.core.windows.net/images/rating_large_12+.png', $result['items'][0]['rating']['image_url']);
        $this->assertEquals('Relativity Media', $result['items'][0]['production']);
        $this->assertEquals('BestFilm.eu OÜ', $result['items'][0]['distributor']['local_name']);
        $this->assertEquals('BestFilm.eu OÜ', $result['items'][0]['distributor']['global_name']);
        $this->assertEquals('Movie', $result['items'][0]['type']);
        $this->assertEquals(['Drāma', 'Detektīvfilma', 'Asa sižeta filma'], $result['items'][0]['genres']);
        $this->assertStringStartsWith('Ītanam Ranneram jau sen nav nevienam jāpierāda, ka ir viens no labākajiem CIP', $result['items'][0]['abstract']);
        $this->assertStringStartsWith('Liks Besons piedāvā kriminālo trilleri', $result['items'][0]['synopsis']);
        $this->assertEquals('http://www.forumcinemas.lv/Event/301312/', $result['items'][0]['url']);

        $this->assertEquals([
            ['title' => '', 'url' => 'm3XIuNdF9XY', 'thumbnail_url' => '', 'type' => 'EventTrailer', 'format' => 'YouTubeVideo']
        ], $result['items'][0]['videos']);

        $this->assertEquals([
            ['title' => 'IMDB', 'url' => 'http://www.imdb.com/title/tt2172934/', 'type' => 'General'],
            ['title' => 'Oficiālā mājas lapa', 'url' => 'http://3daystokill.tumblr.com/', 'type' => 'EventOfficialHomepage'],
            ['title' => 'Facebook', 'url' => 'https://www.facebook.com/3daystokillmovie', 'type' => 'General'],
        ], $result['items'][0]['links']);

        $this->assertEquals([
            ['title' => '', 'url' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/gallery/3DaystoKill_010.JPG', 'thumbnail_url' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/gallery/THUMB_3DaystoKill_010.JPG'],
            ['title' => '', 'url' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/gallery/3DaystoKill_011.JPG', 'thumbnail_url' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/gallery/THUMB_3DaystoKill_011.JPG'],
        ], $result['items'][0]['gallery']);

        // Images should be merged with pictures.
        $this->assertEquals([
            'micro_portrait' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/portrait_micro/20140418_3daystokill.jpg',
            'small_portrait' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/portrait_small/20140418_3daystokill.jpg',
            'large_portrait' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/portrait_large/20140418_3daystokill.jpg',
            'large_landscape' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/landscape_large/3daystk_670.jpg',
            'fullhd_portrait' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/portrait_fullhd/20140418_3daystokill.jpg',
            'hd_portrait' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/portrait_hd/20140418_3daystokill.jpg',
            'extralarge_portrait' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/portrait_xlarge/20140418_3daystokill.jpg',
            'medium_portrait' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/portrait_medium/20140418_3daystokill.jpg',
            'poster' => 'http://forumcinemaslv.blob.core.windows.net/1012/Event_7619/poster/20140418_3daystokill.jpg',
        ], $result['items'][0]['images']);
    }

    public function testShowsWithDefaultArguments()
    {
        $this->getClient('shows')->shows();

        $this->assertEquals(['nrOfDays' => 1], $this->history->getLastRequest()->getQuery()->toArray());
    }

    public function testShowsWithArguments()
    {
        $this->getClient('shows')->shows(['date' => '2014-04-05', 'days_from_date' => 12, 'area' => 1000, 'event' => 5]);

        $this->assertEquals([
            'dt' => '05.04.2014',
            'nrOfDays' => 12,
            'area' => 1000,
            'eventID' => 5
        ], $this->history->getLastRequest()->getQuery()->toArray());
    }

    public function testShows()
    {
        $result = $this->getClient('shows')->shows();

        $this->assertArrayHasKey('published', $result);
        $this->assertArrayHasKey('items', $result);
        $this->assertCount(4, $result['items']);

        $this->assertEquals('2014-04-27', $result['published']);
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
