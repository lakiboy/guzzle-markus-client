<?php

namespace Devmachine\Tests\Guzzle\Markus;

use Devmachine\Guzzle\Markus\MarkusDescription;

class MarkusDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetBaseUrl()
    {
        $description = new MarkusDescription('http://forumcinemas.lv/xml');
        $this->assertEquals('http://forumcinemas.lv/xml/', $description->getBaseUrl());

        $description = new MarkusDescription('http://forumcinemas.lv/xml/');
        $this->assertEquals('http://forumcinemas.lv/xml/', $description->getBaseUrl());
    }
}
