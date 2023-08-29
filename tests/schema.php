<?php
declare(strict_types=1);

return [
    'obfuscate_articles' => [
        'columns' => [
            'id' => ['type' => 'integer'],
            'author_id' => ['type' => 'integer'],
            'title' => ['type' => 'string', 'null' => false],
            'created' => ['type' => 'datetime', 'null' => true],
            'modified' => ['type' => 'datetime', 'null' => true],
        ],
        'constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ],
    'obfuscate_articles_tags' => [
        'columns' => [
            'id' => ['type' => 'integer'],
            'obfuscate_article_id' => ['type' => 'integer'],
            'tag_id' => ['type' => 'integer'],
        ],
        'constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ],
    'obfuscate_authors' => [
        'columns' => [
            'id' => ['type' => 'integer'],
            'uuid' => ['type' => 'string'],
            'name' => ['type' => 'string', 'null' => false],
            'created' => ['type' => 'datetime', 'null' => true],
            'modified' => ['type' => 'datetime', 'null' => true],
        ],
        'constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ],
    'obfuscate_comments' => [
        'columns' => [
            'id' => ['type' => 'integer'],
            'obfuscate_article_id' => ['type' => 'integer'],
            'title' => ['type' => 'string', 'null' => false],
            'created' => ['type' => 'datetime', 'null' => true],
            'modified' => ['type' => 'datetime', 'null' => true],
        ],
        'constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ],
    'obfuscate_tags' => [
        'columns' => [
            'id' => ['type' => 'integer'],
            'name' => ['type' => 'string', 'null' => false],
            'created' => ['type' => 'datetime', 'null' => true],
            'modified' => ['type' => 'datetime', 'null' => true],
        ],
        'constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ],
];
