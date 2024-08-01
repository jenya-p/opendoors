<?php
return [
    'home.partners' => [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'url' => [
                    'type' => 'url',
                    'label' => 'URL',
                    'translable' => true,
                ],
                'title' => [
                    'type' => 'string',
                    'label' => 'Название',
                    'translable' => true,
                ],
                'image_id' => [
                    'type' => 'attachment',
                    'label' => 'Изображение',
                    'translable' => true,
                ]
            ]
        ]
    ],
    'home.top' => [
        'type' => 'object',
        'properties' => [
            'h1' => [
                'type' => 'ml-string',
                'label' => 'Слоган',
                'translable' => true,
            ],
            'description' => [
                'type' => 'editor',
                'label' => 'Текст',
                'translable' => true,
            ],
        ]
    ],
    'footer' => [
        'type' => 'object',
        'properties' => [
            'vk' => [
                'type' => 'url',
                'label' => 'Ссылка на VK',
            ],
            'telegram' => [
                'type' => 'url',
                'label' => 'Ссылка на Телеграмм',
            ],
            'email' => [
                'type' => 'email',
                'label' => 'Email',
            ],
            'address' => [
                'type' => 'ml-string',
                'label' => 'Адрес',
                'translable' => true,
            ],
        ]
    ],
    'about' => [
        'type' => 'object',
        'properties' => [
            'h1' => [
                'type' => 'string',
                'label' => 'Заголовок',
                'translable' => true
            ],
            'content' => [
                'type' => 'editor',
                'label' => 'Текст',
                'translable' => true
            ],
            'webinars' => [
                'type' => 'array',
                'label' => 'Вебинары',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => [
                            'type' => 'ml-string',
                            'label' => 'Название',
                            'translable' => true
                        ],
                        'youtube' => [
                            'type' => 'string',
                            'label' => 'Код ролика на Youtube',
                        ],
                    ]
                ]
            ],

        ]
    ],
    'rules' => [
        'type' => 'object',
        'properties' => [
            'title' => [
                'type' => 'string',
                'label' => 'Заголовок',
                'translable' => true
            ],
            'blocks' => [
                'type' => 'array',
                'label' => 'Блоки',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => [
                            'type' => 'ml-string',
                            'label' => 'Название',
                            'translable' => true
                        ],
                        'summary' => [
                            'type' => 'ml-string',
                            'label' => 'Описание',
                            'translable' => true
                        ],
                        'image_id' => [
                            'type' => 'attachment',
                            'label' => 'Изображение',
                            'translable' => true,
                        ]
                    ]
                ]
            ],

        ]
    ],
    'tracks' => [
        'type' => 'object',
        'properties' => [
            'h1' => [
                'type' => 'ml-string',
                'label' => 'Заголовок',
                'translable' => true
            ],
            'content' => [
                'type' => 'editor',
                'label' => 'Текст',
                'translable' => true
            ]
        ]
    ]
];
