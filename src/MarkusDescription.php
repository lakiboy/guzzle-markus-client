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
                ]
            ],
            'models' => [
                'AreasOutput' => [
                    'type' => 'object',
                    'properties' => [
                        'items' => [
                            'type' => 'array',
                            'location' => 'xml',
                            'sentAs' => 'TheatreArea',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'id' => [
                                        'type' => 'integer',
                                        'sentAs' => 'ID'
                                    ],
                                    'name' => [
                                        'type' => 'string',
                                        'sentAs' => 'Name'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'LanguagesOutput' => [
                    'type' => 'object',
                    'properties' => [
                        'items' => [
                            'type' => 'array',
                            'location' => 'xml',
                            'sentAs' => 'Language',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'id' => [
                                        'type' => 'integer',
                                        'sentAs' => 'ID'
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
                        ]
                    ]
                ]
            ]
        ]);
    }
}
