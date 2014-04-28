# Guzzle Client for MCS API

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

This guzzle client tries to fix various inconsitences of XML API, regroup, rename and filter returned results. Basically to make it look like you deal with fine-tuned JSON API.

#### Installation

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
