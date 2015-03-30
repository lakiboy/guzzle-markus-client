<?php

namespace Devmachine\Guzzle\Markus;

use GuzzleHttp\Command\Guzzle\Description;

class MarkusDescription extends Description
{
    private static $documentationUrl = 'http://www.forumcinemas.lv/xml';

    public function __construct($baseUrl)
    {
        parent::__construct([
            'baseUrl' => rtrim($baseUrl, '/').'/',
            'name' => 'Markus',
            'description' => 'Markus Cinema System API - http://www.markus.ee',
            'apiVersion' => '1.0',
            'operations' => [
                'areas' => [
                    'httpMethod' => 'GET',
                    'uri' => 'TheatreAreas',
                    'responseModel' => 'AreasOutput',
                    'documentationUrl' => self::$documentationUrl,
                ],
                'languages' => [
                    'httpMethod' => 'GET',
                    'uri' => 'Languages',
                    'responseModel' => 'LanguagesOutput',
                    'documentationUrl' => self::$documentationUrl,
                ],
                'schedule' => [
                    'httpMethod' => 'GET',
                    'uri' => 'ScheduleDates',
                    'responseModel' => 'ScheduleOutput',
                    'documentationUrl' => self::$documentationUrl,
                    'parameters' => [
                        'area' => [
                            '$ref' => 'AreaParameter',
                            'description' => 'Defaults to first area in the list.',
                        ],
                    ],
                ],
                'articleCategories' => [
                    'httpMethod' => 'GET',
                    'uri' => 'NewsCategories',
                    'responseModel' => 'ArticleCategoriesOutput',
                    'documentationUrl' => self::$documentationUrl,
                    'parameters' => [
                        'area' => [
                            '$ref' => 'AreaParameter',
                        ],
                    ],
                ],
                'articles' => [
                    'httpMethod' => 'GET',
                    'uri' => 'News',
                    'responseModel' => 'ArticlesOutput',
                    'documentationUrl' => self::$documentationUrl,
                    'parameters' => [
                        'area' => [
                            '$ref' => 'AreaParameter',
                        ],
                        'event' => [
                            '$ref' => 'EventParameter',
                            'description' => 'When specified "category" parameter has no effect.',
                        ],
                        'category' => [
                            'type' => 'integer',
                            'location' => 'query',
                            'sentAs' => 'categoryID',
                        ],
                    ],
                ],
                'events' => [
                    'httpMethod' => 'GET',
                    'uri' => 'Events',
                    'responseModel' => 'EventsOutput',
                    'documentationUrl' => self::$documentationUrl,
                    'parameters' => [
                        'area' => [
                            '$ref' => 'AreaParameter',
                            'description' => 'Has no effect when "coming_soon" parameter is set to true.',
                        ],
                        'id' => [
                            '$ref' => 'EventParameter',
                            'description' => 'When specified "area" and "coming_soon" parameters have no effect.',
                        ],
                        'include_videos' => [
                            'sentAs' => 'includeVideos',
                            'type' => 'boolean',
                            'location' => 'query',
                            'default' => false,
                            'format' => 'boolean-string',
                            'description' => 'Include video data.',
                        ],
                        'include_links' => [
                            'sentAs' => 'includeLinks',
                            'type' => 'boolean',
                            'location' => 'query',
                            'default' => false,
                            'format' => 'boolean-string',
                            'description' => 'Include links data.',
                        ],
                        'include_gallery' => [
                            'sentAs' => 'includeGallery',
                            'type' => 'boolean',
                            'location' => 'query',
                            'default' => false,
                            'format' => 'boolean-string',
                            'description' => 'Include gallery data.',
                        ],
                        'all_images' => [
                            'sentAs' => 'includePictures',
                            'type' => 'boolean',
                            'location' => 'query',
                            'default' => false,
                            'format' => 'boolean-string',
                            'description' => 'Fetch all available images (except gallery).',
                        ],
                        'coming_soon' => [
                            'sentAs' => 'listType',
                            'type' => 'boolean',
                            'location' => 'query',
                            'description' => 'Show upcoming events.',
                            'default' => false,
                            'filters' => [function ($val) { return $val ? 'ComingSoon' : 'NowInTheatres'; }],
                        ],
                    ],
                ],
                'shows' => [
                    'httpMethod' => 'GET',
                    'uri' => 'Schedule',
                    'responseModel' => 'ShowsOutput',
                    'documentationUrl' => self::$documentationUrl,
                    'parameters' => [
                        'area' => [
                            '$ref' => 'AreaParameter',
                            'description' => 'Defaults to first area in the list.',
                        ],
                        'event' => [
                            '$ref' => 'EventParameter',
                        ],
                        'date' => [
                            'type' => 'string',
                            'location' => 'query',
                            'sentAs' => 'dt',
                            'description' => 'Defaults to today.',
                            'filters' => [
                                ['method' => 'Devmachine\Guzzle\Markus\Util::formatDate', 'args' => ['@value', 'd.m.Y']],
                            ],
                        ],
                        'days_from_date' => [
                            'type' => 'integer',
                            'location' => 'query',
                            'sentAs' => 'nrOfDays',
                            'default' => 1,
                            'minimum' => 1,
                            'maximum' => 31,
                            'description' => 'Amount of days to include from date.',
                        ],
                    ],
                ],
            ],
            'models' => [
                'AreaParameter' => [
                    'type' => 'integer',
                    'location' => 'query',
                ],
                'EventParameter' => [
                    'type' => 'integer',
                    'location' => 'query',
                    'sentAs' => 'eventID',
                ],

                'DateProperty' => [
                    'type' => 'string',
                    'filters' => [
                        ['method' => 'Devmachine\Guzzle\Markus\Util::formatDate', 'args' => ['@value']],
                    ],
                ],

                'AreasOutput' => [
                    'name' => 'items',
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'TheatreArea',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                                'sentAs' => 'ID',
                            ],
                            'name' => [
                                'type' => 'string',
                                'sentAs' => 'Name',
                            ],
                        ],
                    ],
                ],
                'LanguagesOutput' => [
                    'name' => 'items',
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'Language',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                                'sentAs' => 'ID',
                            ],
                            'name' => [
                                'type' => 'string',
                                'sentAs' => 'Name',
                            ],
                            'local_name' => [
                                'type' => 'string',
                                'sentAs' => 'LocalName',
                            ],
                            'original_name' => [
                                'type' => 'string',
                                'sentAs' => 'NameInLanguage',
                            ],
                            'code' => [
                                'type' => 'string',
                                'sentAs' => 'ISOTwoLetterCode',
                            ],
                            'three_letter_code' => [
                                'type' => 'string',
                                'sentAs' => 'ISOCode',
                            ],
                        ],
                    ],
                ],
                'ScheduleOutput' => [
                    'name' => 'items',
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'dateTime',
                    'items' => [
                        '$ref' => 'DateProperty',
                    ],
                ],
                'ArticleCategoriesOutput' => [
                    'name' => 'items',
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'NewsArticleCategory',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                                'sentAs' => 'ID',
                            ],
                            'name' => [
                                'type' => 'string',
                                'sentAs' => 'Name',
                            ],
                            'article_count' => [
                                'type' => 'integer',
                                'sentAs' => 'NewsArticleCount',
                            ],
                        ],
                    ],
                ],
                'ArticlesOutput' => [
                    'name' => 'items',
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'NewsArticle',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => [
                                'type' => 'string',
                                'sentAs' => 'Title',
                            ],
                            'published' => [
                                '$ref' => 'DateProperty',
                                'sentAs' => 'PublishDate',
                            ],
                            'abstract' => [
                                'type' => 'string',
                                'sentAs' => 'HTMLLead',
                                'filters' => [
                                    ['method' => 'trim', 'args' => ['@value']],
                                ],
                            ],
                            'content' => [
                                'type' => 'string',
                                'sentAs' => 'HTMLContent',
                                'filters' => [
                                    ['method' => 'trim', 'args' => ['@value']],
                                ],
                            ],
                            'url' => [
                                'type' => 'string',
                                'sentAs' => 'ArticleURL',
                            ],
                            'image_url' => [
                                'type' => 'string',
                                'sentAs' => 'ImageURL',
                            ],
                            'thumbnail_url' => [
                                'type' => 'string',
                                'sentAs' => 'ThumbnailURL',
                            ],
                            'event' => [
                                'type' => 'integer',
                                'sentAs' => 'EventID',
                            ],
                            'categories' => [
                                'type' => 'object',
                                'sentAs' => 'Categories',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::getArrayElement', 'args' => ['@value', 'items']],
                                ],
                                'properties' => [
                                    'items' => [
                                        '$ref' => 'ArticleCategoriesOutput',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'EventsOutput' => [
                    'name' => 'items',
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'Event',
                    'items' => [
                        'type' => 'object',
                        'additionalProperties' => false,
                        'filters' => [
                            ['method' => 'Devmachine\Guzzle\Markus\Util::groupParameters', 'args' => ['@value', ['rating', 'distributor']]],
                            ['method' => 'Devmachine\Guzzle\Markus\Util::mergePicturesWithImages', 'args' => ['@value']],
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                                'sentAs' => 'ID',
                            ],
                            'title' => [
                                'type' => 'string',
                                'sentAs' => 'Title',
                            ],
                            'original_title' => [
                                'type' => 'string',
                                'sentAs' => 'OriginalTitle',
                            ],
                            'year' => [
                                'type' => 'integer',
                                'sentAs' => 'ProductionYear',
                                'filters' => [
                                    'intval',
                                ],
                            ],
                            'length' => [
                                'type' => 'integer',
                                'sentAs' => 'LengthInMinutes',
                                'filters' => [
                                    'intval',
                                ],
                            ],
                            'release_date' => [
                                'sentAs' => 'dtLocalRelease',
                                '$ref' => 'DateProperty',
                            ],
                            'rating_name' => [
                                'type' => 'string',
                                'sentAs' => 'RatingLabel',
                            ],
                            'rating_description' => [
                                'type' => 'string',
                                'sentAs' => 'Rating',
                            ],
                            'rating_image_url' => [
                                'type' => 'string',
                                'sentAs' => 'RatingImageUrl',
                            ],
                            'distributor_local_name' => [
                                'type' => 'string',
                                'sentAs' => 'LocalDistributorName',
                            ],
                            'distributor_global_name' => [
                                'type' => 'string',
                                'sentAs' => 'GlobalDistributorName',
                            ],
                            'production' => [
                                'type' => 'string',
                                'sentAs' => 'ProductionCompanies',
                            ],
                            'type' => [
                                'type' => 'string',
                                'sentAs' => 'EventType',
                            ],
                            'genres' => [
                                'type' => 'string',
                                'sentAs' => 'Genres',
                                'filters' => [
                                    ['method' => 'explode', 'args' => [', ', '@value']],
                                ],
                            ],
                            'url' => [
                                'type' => 'string',
                                'sentAs' => 'EventURL',
                            ],
                            'abstract' => [
                                'type' => 'string',
                                'sentAs' => 'ShortSynopsis',
                                'filters' => [
                                    ['method' => 'trim', 'args' => ['@value']],
                                ],
                            ],
                            'synopsis' => [
                                'type' => 'string',
                                'sentAs' => 'Synopsis',
                                'filters' => [
                                    ['method' => 'trim', 'args' => ['@value']],
                                ],
                            ],
                            'images' => [
                                'type' => 'object',
                                'sentAs' => 'Images',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::renameImageFormats', 'args' => ['@value']],
                                ],
                            ],
                            'pictures' => [
                                'type' => 'object',
                                'sentAs' => 'Pictures',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::getArrayElement', 'args' => ['@value', 'items']],
                                ],
                                'properties' => [
                                    'items' => [
                                        'type' => 'array',
                                        'sentAs' => 'Picture',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'title' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Title',
                                                ],
                                                'url' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Location',
                                                ],
                                                'type' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'PictureType',
                                                    'filters' => [
                                                        ['method' => 'Devmachine\Guzzle\Markus\Util::renameImageFormat', 'args' => ['@value']],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'videos' => [
                                'type' => 'object',
                                'sentAs' => 'Videos',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::getArrayElement', 'args' => ['@value', 'items']],
                                ],
                                'properties' => [
                                    'items' => [
                                        'type' => 'array',
                                        'sentAs' => 'EventVideo',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'title' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Title',
                                                ],
                                                'url' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Location',
                                                ],
                                                'thumbnail_url' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'ThumbnailLocation',
                                                ],
                                                'type' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'MediaResourceSubType',
                                                ],
                                                'format' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'MediaResourceFormat',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'links' => [
                                'type' => 'object',
                                'sentAs' => 'Links',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::getArrayElement', 'args' => ['@value', 'items']],
                                ],
                                'properties' => [
                                    'items' => [
                                        'type' => 'array',
                                        'sentAs' => 'Link',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'title' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Title',
                                                ],
                                                'url' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Location',
                                                ],
                                                'type' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'LinkType',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'gallery' => [
                                'type' => 'object',
                                'sentAs' => 'Gallery',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::getArrayElement', 'args' => ['@value', 'items']],
                                ],
                                'properties' => [
                                    'items' => [
                                        'type' => 'array',
                                        'sentAs' => 'GalleryImage',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'title' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Title',
                                                ],
                                                'url' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'Location',
                                                ],
                                                'thumbnail_url' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'ThumbnailLocation',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'actors' => [
                                'type' => 'object',
                                'sentAs' => 'Cast',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::getArrayElement', 'args' => ['@value', 'items']],
                                ],
                                'properties' => [
                                    'items' => [
                                        'type' => 'array',
                                        'sentAs' => 'Actor',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'first_name' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'FirstName',
                                                ],
                                                'last_name' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'LastName',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'directors' => [
                                'type' => 'object',
                                'sentAs' => 'Directors',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::getArrayElement', 'args' => ['@value', 'items']],
                                ],
                                'properties' => [
                                    'items' => [
                                        'type' => 'array',
                                        'sentAs' => 'Director',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'first_name' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'FirstName',
                                                ],
                                                'last_name' => [
                                                    'type' => 'string',
                                                    'sentAs' => 'LastName',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'ShowsOutput' => [
                    'type' => 'object',
                    'properties' => [
                        'published' => [
                            'sentAs' => 'PubDate',
                            '$ref' => 'DateProperty',
                            'location' => 'xml',
                        ],
                        'items' => [
                            'type' => 'array',
                            'location' => 'xml',
                            'sentAs' => 'Shows',
                            'items' => [
                                'type' => 'object',
                                'sentAs' => 'Show',
                                'filters' => [
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::groupParameters', 'args' => ['@value', ['event_rating']]],
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::groupParameters', 'args' => ['@value', ['event', 'presentation', 'area', 'auditorium']]],
                                    ['method' => 'Devmachine\Guzzle\Markus\Util::fixShowEndTimeUTC', 'args' => ['@value']],
                                ],
                                'additionalProperties' => false,
                                'properties' => [
                                    'id' => [
                                        'type' => 'integer',
                                        'sentAs' => 'ID',
                                    ],
                                    'url' => [
                                        'type' => 'string',
                                        'sentAs' => 'ShowURL',
                                    ],
                                    'series' => [
                                        'type' => 'string',
                                        'sentAs' => 'EventSeries',
                                    ],
                                    'event_title' => [
                                        'type' => 'string',
                                        'sentAs' => 'Title',
                                    ],
                                    'event_original_title' => [
                                        'type' => 'string',
                                        'sentAs' => 'OriginalTitle',
                                    ],
                                    'event_year' => [
                                        'type' => 'integer',
                                        'sentAs' => 'ProductionYear',
                                    ],
                                    'event_length' => [
                                        'type' => 'integer',
                                        'sentAs' => 'LengthInMinutes',
                                    ],
                                    'event_release_date' => [
                                        'sentAs' => 'dtLocalRelease',
                                        '$ref' => 'DateProperty',
                                    ],
                                    'event_rating_name' => [
                                        'type' => 'string',
                                        'sentAs' => 'RatingLabel',
                                    ],
                                    'event_rating_description' => [
                                        'type' => 'string',
                                        'sentAs' => 'Rating',
                                    ],
                                    'event_rating_image_url' => [
                                        'type' => 'string',
                                        'sentAs' => 'RatingImageUrl',
                                    ],
                                    'event_type' => [
                                        'type' => 'string',
                                        'sentAs' => 'EventType',
                                    ],
                                    'event_genres' => [
                                        'type' => 'string',
                                        'sentAs' => 'Genres',
                                        'filters' => [
                                            ['method' => 'explode', 'args' => [', ', '@value']],
                                        ],
                                    ],
                                    'event_id' => [
                                        'type' => 'integer',
                                        'sentAs' => 'EventID',
                                    ],
                                    'event_url' => [
                                        'type' => 'string',
                                        'sentAs' => 'EventURL',
                                    ],
                                    'event_images' => [
                                        'type' => 'object',
                                        'sentAs' => 'Images',
                                        'filters' => [
                                            ['method' => 'Devmachine\Guzzle\Markus\Util::renameImageFormats', 'args' => ['@value']],
                                        ],
                                    ],
                                    'presentation_description' => [
                                        'type' => 'string',
                                        'sentAs' => 'PresentationMethodAndLanguage',
                                    ],
                                    'presentation_method' => [
                                        'type' => 'string',
                                        'sentAs' => 'PresentationMethod',
                                    ],
                                    'area_id' => [
                                        'type' => 'integer',
                                        'sentAs' => 'TheatreID',
                                    ],
                                    'area_name' => [
                                        'type' => 'string',
                                        'sentAs' => 'Theatre',
                                    ],
                                    'auditorium_id' => [
                                        'type' => 'integer',
                                        'sentAs' => 'TheatreAuditriumID',
                                    ],
                                    'auditorium_name' => [
                                        'type' => 'string',
                                        'sentAs' => 'TheatreAuditorium',
                                    ],
                                    'auditorium_full_name' => [
                                        'type' => 'string',
                                        'sentAs' => 'TheatreAndAuditorium',
                                    ],
                                    'date' => [
                                        '$ref' => 'DateProperty',
                                        'sentAs' => 'dtAccounting',
                                    ],
                                    'sales_start_time' => [
                                        'type' => 'string',
                                        'sentAs' => 'ShowSalesStartTime',
                                    ],
                                    'sales_start_time_utc' => [
                                        'type' => 'string',
                                        'sentAs' => 'ShowSalesStartTimeUTC',
                                    ],
                                    'sales_end_time' => [
                                        'type' => 'string',
                                        'sentAs' => 'ShowSalesEndTime',
                                    ],
                                    'sales_end_time_utc' => [
                                        'type' => 'string',
                                        'sentAs' => 'ShowSalesEndTimeUTC',
                                    ],
                                    'start_time' => [
                                        'type' => 'string',
                                        'sentAs' => 'dttmShowStart',
                                    ],
                                    'start_time_utc' => [
                                        'type' => 'string',
                                        'sentAs' => 'dttmShowStartUTC',
                                    ],
                                    'end_time' => [
                                        'type' => 'string',
                                        'sentAs' => 'dttmShowEnd',
                                    ],
                                    'end_time_utc' => [
                                        'type' => 'string',
                                        'sentAs' => 'dttmShowEndUTC',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
