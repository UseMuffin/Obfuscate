<?php
namespace Muffin\Obfuscate\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ArticlesTagsFixture extends TestFixture
{
    public $table = 'obfuscate_articles_tags';

    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'obfuscate_article_id' => ['type' => 'integer'],
        'tag_id' => ['type' => 'integer'],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    /**
     * records property
     *
     * @var array
     */
    public $records = [
        ['obfuscate_article_id' => 1, 'tag_id' => 1],
        ['obfuscate_article_id' => 1, 'tag_id' => 2],
        ['obfuscate_article_id' => 2, 'tag_id' => 2],
    ];
}
