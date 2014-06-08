# Guzzle Client for MCS API

[![Build Status](https://travis-ci.org/dev-machine/guzzle-markus-client.svg?branch=master)](https://travis-ci.org/dev-machine/guzzle-markus-client)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f969371b-7be2-4f5c-b3b7-7e36f335dcc1/mini.png)](https://insight.sensiolabs.com/projects/f969371b-7be2-4f5c-b3b7-7e36f335dcc1)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-machine/guzzle-markus-client/badges/quality-score.png?s=4b2753b29c4578f89f58d02555c9f22eba96e739)](https://scrutinizer-ci.com/g/dev-machine/guzzle-markus-client/)

Guzzle client for Markus Cinema System installations - http://www.markus.ee/. Used in Baltics, Finland, Iceland and Malta. In Latvia, Lithuania and Estonia best known as _ForumCinemas_ chain.

#### Cinemas list (not complete):

 - http://www.forumcinemas.lv/XML
 - http://www.forumcinemas.lt/XML
 - http://www.forumcinemas.ee/XML
 - http://www.finnkino.fi/XML
 - http://www.sambio.is/XML
 - http://www.edencinemas.com.mt/XML (incomplete and untested)
 - http://www.cinamonkino.lv/XML (Riga)
 - http://www.multikino.lv/XML (Riga)

There are more MCS installations (e.g. http://www.silverscreen.lv), but not all of them have a public XML API. If you happen to know any working XML URLs - create a pull request.

#### Description

This guzzle client tries to fix various inconsistencies of XML API, regroup, rename and filter returned results. Basically to make it look like you deal with fine-tuned JSON API.

## Installation

This library can be installed using Composer. Add the following to your composer.json:

```javascript
{
    "require": {
        "devmachine/guzzle-markus-client": "1.0.*"
    }
}
```

## Terminology

Some terminology explained used in MCS API:

 - `area` - represents a movie theatre,
 - `event` - represents a movie,
 - `show` - represents a show time of a movie i.e. theatre (area), auditorium, movie (event) and date/time of the show.

## Sample usage

```php
use Devmachine\Guzzle\Markus\MarkusClient;

$client = MarkusClient::factory('http://forumcinemas.ee/xml');
$result = $client->areas();

var_dump($result['items']);
```

Sample output:

```
array(4) {
  [0] =>
  array(2) {
    'id' =>
    string(4) "1002"
    'name' =>
    string(21) "Tallinn - kõik kinod"
  }
  [1] =>
  array(2) {
    'id' =>
    string(4) "1008"
    'name' =>
    string(15) "Coca-Cola Plaza"
  }
  [2] =>
  array(2) {
    'id' =>
    string(4) "1005"
    'name' =>
    string(19) "Tartu - Kino Ekraan"
  }
  [3] =>
  array(2) {
    'id' =>
    string(4) "1004"
    'name' =>
    string(18) "Narva - Kino Astri"
  }
}
```

## API methods

There are no required parameters. Below there is an example with all possible arguments for each method.

```php
use Devmachine\Guzzle\Markus\MarkusClient;

$client = MarkusClient::factory('http://forumcinemas.ee/xml');

// Get list of theatres
$result = $client->areas();

// Get list of languages.
$result = $client->languages();

// Get list of show dates.
$result = $client->schedule([
    'area' => $areaId // Defaults to first area in the list.
]);

// Get list of article categories.
$result = $client->articleCategories([
    'area' => $areaId // Filter by area.
]);

// Get list of articles.
$result = $client->articles([
    'area'     => $areaId,    // Filter by area.
    'event'    => $eventId,   // When specified "category" parameter has no effect.
    'category' => $categoryId // Filter by category.
]);

// Get list of events.
$result = $client->events([
    'area'            => $areaId,  // Has no effect when "coming_soon" parameter is set to true.
    'id'              => $eventId, // When specified "area" and "coming_soon" parameters have no effect.
    'include_videos'  => true,     // Include video data. Defaults to false.
    'include_links'   => true,     // Include links data. Defaults to false.
    'include_gallery' => true,     // Include gallery data. Defaults to false.
    'all_images'      => true,     // Fetch all available images (except gallery). Defaults to false.
    'coming_soon'     => true      // Show upcoming events. Defaults to false.
]);

// Get list of shows.
$result = $client->shows([
    'area'           => $areaId,      // Defaults to first area in the list.
    'event'          => $eventId,     // Filter by event.
    'date'           => '2014-04-28', // Defaults to today.
    'days_from_date' => 2,            // Amount of days to include from date. Defaults to 1.
]);
```

## Image formats

Accoding to findings MCS API has consistent images naming/dimensions.

Name    | Portrait  | Landscape
--------| ----------|----------
micro   | 59x87     | 88x31
small   | 99x146    | 148x100
medium  | 320x480   | 310x150
large   | 480x711   | 670x250
xlarge  | 640x948   | 851x315
hd      | 720x1280  | 1280x720
full hd | 1080x1920 | 1920x1080
poster  | 768x1097  | -
