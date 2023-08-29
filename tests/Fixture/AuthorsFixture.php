<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class AuthorsFixture extends TestFixture
{
    public string $table = 'obfuscate_authors';

    /**
     * records property
     *
     * @var array
     */
    public array $records = [
        ['name' => 'John', 'uuid' => 'f1f88079-ec15-4863-ad41-7e85cfa98f3d'],
        ['name' => 'Jane', 'uuid' => 'a2234d7c-6de7-4b28-aa34-39d57f3d35e3'],
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
