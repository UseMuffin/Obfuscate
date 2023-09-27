<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ArticlesFixture extends TestFixture
{
    public string $table = 'obfuscate_articles';

    /**
     * records property
     *
     * @var array
     */
    public array $records = [
        ['author_id' => 1, 'title' => 'First Article'],
        ['author_id' => 2, 'title' => 'Second Article'],
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
