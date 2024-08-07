<?php

return [
    "ru_lang" => [
        "type" => "object",
        "properties" => [
            "speak" => [
                "label" => "Владение русским языком",
                "required" => true,
                "type" => "boolean"
            ],
            "level" => [
                "label" => "Уровень владения",
                "required" => true,
                "type" => "select",
                "options" => "language_levels_ru"
            ],
            "file" => [
                "label" => "Загрузка файла (pdf, jpg) (при наличии)",
                "required" => false,
                "type" => "attachment"
            ],
            "title" => [
                "label" => "Наименование награды/сертификата",
                "required" => true,
                "type" => "ml-string"
            ],
            "issued_by" => [
                "label" => "Кем выдано",
                "required" => true,
                "type" => "ml-string"
            ],
            "year" => [
                "label" => "Год получения",
                "required" => true,
                "type" => "year",
            ],
            "certificate" => [
                "label" => "Наличие сертификата",
                "required" => true,
                "type" => "boolean"
            ]
        ]
    ],
    "en_lang" => [
        "type" => "object",
        "properties" => [
            "speak" => [
                "label" => "Владение английским языком",
                "required" => true,
                "type" => "boolean"
            ],
            "level" => [
                "label" => "Уровень владения",
                "required" => true,
                "type" => "select",
                "options" => "language_levels_en"
            ],
            "file" => [
                "label" => "Загрузка файла (pdf, jpg) (при наличии)",
                "required" => false,
                "type" => "attachment"
            ],
            "title" => [
                "label" => "Наименование награды/сертификата",
                "required" => true,
                "type" => "ml-string"
            ],
            "issued_by" => [
                "label" => "Кем выдано",
                "required" => true,
                "type" => "ml-string"
            ],
            "year" => [
                "label" => "Год получения",
                "required" => true,
                "type" => "year",
            ],
            "certificate" => [
                "label" => "Наличие сертификата",
                "required" => true,
                "type" => "boolean"
            ]
        ]
    ],
    "published_articles" => [
        "type" => "object",
        "properties" => [
            "title" => [
                "label" => "Заголовок",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "authors" => [
                "label" => "Авторы",
                "required" => true,
                "type" => "ml-string"
            ],
            "journal" => [
                "label" => "Журнал",
                "required" => true,
                "type" => "ml-string"
            ],
            "year" => [
                "label" => "Год",
                "required" => true,
                "type" => "year",
            ],
            "quarter" => [
                "label" => "Квартиль",
                "required" => false,
                "type" => "string"
            ],
            "doi" => [
                "label" => "DOI",
                "required" => false,
                "type" => "string"
            ],
            "link" => [
                "label" => "Ссылка на статью/сайт журнала",
                "required" => true,
                "type" => "string"
            ],
            "files" => [
                "label" => "Загрузка файла подтверждения (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ],
            "type" => [
                "type" => "select",
                "options" => "publication_types",
                "label" => "Вид публикации",
                "required" => true
            ],
            "scopus" => [
                "label" => "Scopus ID",
                "required" => false,
                "type" => "string"
            ],
            "spin" => [
                "label" => "SPIN",
                "required" => false,
                "type" => "string"
            ],
            "orcid" => [
                "label" => "ORCID",
                "required" => false,
                "type" => "string"
            ],
            "researcher" => [
                "label" => "Researcher ID",
                "required" => false,
                "type" => "string"
            ]
        ]
    ],
    "conference_report" => [
        "type" => "object",
        "properties" => [
            "title" => [
                "label" => "Заголовок",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "authors" => [
                "label" => "Авторы",
                "required" => true,
                "type" => "ml-string"
            ],
            "conference" => [
                "label" => "Конференция",
                "required" => true,
                "type" => "ml-string"
            ],
            "year" => [
                "label" => "Год",
                "required" => true,"type" => "year",
            ],
            "website" => [
                "label" => "Сайт",
                "required" => true,
                "type" => "string"
            ],
            "files" => [
                "label" => "Загрузка файла подтверждения (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ]
        ]
    ],
    "patent" => [
        "type" => "object",
        "properties" => [
            "title" => [
                "label" => "Наименование",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "authors" => [
                "label" => "Авторы",
                "required" => true,
                "type" => "ml-string"
            ],
            "issued_by" => [
                "label" => "Кем выдано",
                "required" => true,
                "type" => "ml-string"
            ],
            "files" => [
                "label" => "Загрузка файла подтверждения (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ]
        ]
    ],
    "online_study_sourses" => [
        "type" => "object",
        "properties" => [
            "title" => [
                "label" => "Название",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "website" => [
                "label" => "Ссылка на ресурс",
                "required" => true,
                "type" => "string"
            ],
            "year" => [
                "label" => "Год прохождения курса",
                "required" => true,
                "type" => "year",
            ],
            "files" => [
                "label" => "Загрузка файла сертификата (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ]
        ]
    ],
    "competition_award" => [
        "type" => "object",
        "properties" => [
            "name" => [
                "label" => "Наименование награды/сертификата",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "issued_by" => [
                "label" => "Кем выдано",
                "required" => true,
                "type" => "ml-string"
            ],
            "year" => [
                "label" => "Год получения",
                "required" => true,
                "type" => "year",
            ],
            "files" => [
                "label" => "Загрузка файла подтверждения (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ]
        ]
    ],
    "job_experience" => [
        "type" => "object",
        "properties" => [
            "company" => [
                "label" => "Название компании",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "position" => [
                "label" => "Наименование должности",
                "required" => true,
                "type" => "string"
            ],
            "time" => [
                "label" => "Годы работы",
                "required" => true,
                "type" => "string"
            ],
            "files" => [
                "label" => "Загрузка файла подтверждения (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ]
        ]
    ],
    "project" => [
        "type" => "object",
        "properties" => [
            "title" => [
                "label" => "Наименование проекта",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "description" => [
                "label" => "Описание проекта и роли участника",
                "required" => true,
                "type" => "ml-string"
            ],
            "year" => [
                "label" => "Год реализации проекта",
                "required" => true,
                "type" => "year",
            ],
            "link" => [
                "label" => "Ссылка на сайт проекта (при наличии)",
                "required" => false,
                "type" => "string"
            ],
            "files" => [
                "label" => "Загрузка файла подтверждения (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ]
        ]
    ],
    "other_awards" => [
        "type" => "object",
        "properties" => [
            "title" => [
                "label" => "Наименование награды/сертификата",
                "required" => true,
                "naming" => true,
                "type" => "ml-string"
            ],
            "issued_by" => [
                "label" => "Кем выдано",
                "required" => true,
                "type" => "ml-string"
            ],
            "year" => [
                "label" => "Год получения",
                "required" => true,
                "type" => "year",
            ],
            "files" => [
                "label" => "Загрузка файла подтверждения (pdf, jpg)",
                "required" => true,
                "type" => "attachment"
            ]
        ]
    ]
];

