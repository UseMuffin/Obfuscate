<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CommentsFixture extends TestFixture
{
    public string $table = 'obfuscate_comments';

    /**
     * records property
     *
     * @var array
     */
    public array $records = [
        ['obfuscate_article_id' => 1, 'title' => 'Hello World'],
        ['obfuscate_article_id' => 1, 'title' => 'Hello Universe'],
    ];

    public function init(): void
    {
        $created = $modified = date('Y-m-d H:i:s');
        array_walk($this->records, function (&$record) use ($created, $modified) {
            $record += compact('created', 'modified');
        });
        parent::init();
    }
}
