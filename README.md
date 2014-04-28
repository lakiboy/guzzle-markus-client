# Guzzle Client for MCS API

Guzzle client for Markus Cinema System installations - http://www.markus.ee/. Used in Baltics, Finland, Iceland and Malta. In Latvia, Latvia and Estonia best known as _ForumCinemas_ chain.

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

#### Installation

This library can be installed using Composer. Add the following to your composer.json:

```javascript
{
    "require": {
        "devmachine/guzzle-markus-client": "1.0.*"
    }
}
```
