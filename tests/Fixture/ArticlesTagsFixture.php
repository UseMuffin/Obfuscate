<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ArticlesTagsFixture extends TestFixture
{
    public string $table = 'obfuscate_articles_tags';

    /**
     * records property
     *
     * @var array
     */
    public array $records = [
        ['obfuscate_article_id' => 1, 'tag_id' => 1],
        ['obfuscate_article_id' => 1, 'tag_id' => 2],
        ['obfuscate_article_id' => 2, 'tag_id' => 2],
    ];
}
