<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TagsFixture extends TestFixture
{
    public string $table = 'obfuscate_tags';

    /**
     * records property
     *
     * @var array
     */
    public array $records = [
        ['name' => 'Foo'],
        ['name' => 'Bar'],
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
