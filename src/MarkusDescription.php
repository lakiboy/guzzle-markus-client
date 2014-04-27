<?php

namespace Devmachine\Guzzle\Markus;

use GuzzleHttp\Command\Guzzle\Description;

class MarkusDescription extends Description
{
    private static $documentationUrl = 'http://www.forumcinemas.lv/xml';

    public function __construct($baseUrl)
    {
        parent::__construct([
            'baseUrl' => rtrim($baseUrl, '/') . '/',
            'name' => 'Markus',
            'description' => 'Markus Cinema System API - http://www.markus.ee',
            'apiVersion' => '1.0',
            'operations' => [
                'areas' => [
                    'httpMethod' => 'GET',
                    'uri' => 'TheatreAreas',
                    'responseModel' => 'AreasOutput',
                    'documentationUrl' => self::$documentationUrl
                ],
                'languages' => [
                    'httpMethod' => 'GET',
                    'uri' => 'Languages',
                    'responseModel' => 'LanguagesOutput',
                    'documentationUrl' => self::$documentationUrl
                ],
                'schedule' => [
                    'httpMethod' => 'GET',
                    'uri' => 'ScheduleDates',
                    'responseModel' => 'ScheduleOutput',
                    'documentationUrl' => self::$documentationUrl,
                    'parameters' => [
                        'area' => [
                            '$ref' => 'AreaParameter',
                            'description' => 'Defaults to first area in the list.'
                        ]
                    ]
                ],
                'articleCategories' => [
                    'httpMethod' => 'GET',
                    'uri' => 'NewsCategories',
                    'responseModel' => 'ArticleCategoriesOutput',
                    'documentationUrl' => self::$documentationUrl,
                    'parameters' => [
                        'area' => [
                            '$ref' => 'AreaParameter',
                        ]
                    ]
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
                            'description' => 'When specified "category" parameter has no effect.'
                        ],
                        'category' => [
                            'type' => 'integer',
                            'location' => 'query',
                            'sentAs' => 'categoryID'
                        ]
                    ]
                ]
            ],
            'models' => [
                'AreaParameter' => [
                    'type' => 'integer',
                    'location' => 'query'
                ],
                'EventParameter' => [
                    'type' => 'integer',
                    'location' => 'query',
                    'sentAs' => 'eventID'
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
                                'sentAs' => 'Name'
                            ]
                        ]
                    ]
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
                                'sentAs' => 'Name'
                            ],
                            'local_name' => [
                                'type' => 'string',
                                'sentAs' => 'LocalName'
                            ],
                            'original_name' => [
                                'type' => 'string',
                                'sentAs' => 'NameInLanguage'
                            ],
                            'code' => [
                                'type' => 'string',
                                'sentAs' => 'ISOTwoLetterCode'
                            ],
                            'three_letter_code' => [
                                'type' => 'string',
                                'sentAs' => 'ISOCode'
                            ]
                        ]
                    ]
                ],
                'ScheduleOutput' => [
                    'name' => 'items',
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'dateTime',
                    'items' => [
                        '$ref' => 'DateProperty'
                    ]
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
                                'sentAs' => 'Name'
                            ],
                            'article_count' => [
                                'type' => 'integer',
                                'sentAs' => 'NewsArticleCount'
                            ],
                        ]
                    ]
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
                                'sentAs' => 'Title'
                            ],
                            'published' => [
                                '$ref' => 'DateProperty',
                                'sentAs' => 'PublishDate'
                            ],
                            'abstract' => [
                                'type' => 'string',
                                'sentAs' => 'HTMLLead',
                                'filters' => [
                                    ['method' => 'trim', 'args' => ['@value']],
                                ]
                            ],
                            'content' => [
                                'type' => 'string',
                                'sentAs' => 'HTMLContent',
                                'filters' => [
                                    ['method' => 'trim', 'args' => ['@value']],
                                ]
                            ],
                            'url' => [
                                'type' => 'string',
                                'sentAs' => 'ArticleURL'
                            ],
                            'image_url' => [
                                'type' => 'string',
                                'sentAs' => 'ImageURL'
                            ],
                            'thumbnail_url' => [
                                'type' => 'string',
                                'sentAs' => 'ThumbnailURL'
                            ],
                            'event' => [
                                'type' => 'integer',
                                'sentAs' => 'EventID'
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
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
