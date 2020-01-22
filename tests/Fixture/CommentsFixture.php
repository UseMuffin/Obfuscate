<?php
namespace Muffin\Obfuscate\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CommentsFixture extends TestFixture
{
    public $table = 'obfuscate_comments';

    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'obfuscate_article_id' => ['type' => 'integer'],
        'title' => ['type' => 'string', 'null' => false],
        'created' => ['type' => 'datetime', 'null' => true],
        'modified' => ['type' => 'datetime', 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    /**
     * records property
     *
     * @var array
     */
    public $records = [
        ['obfuscate_article_id' => 1, 'title' => 'Hello World'],
        ['obfuscate_article_id' => 1, 'title' => 'Hello Universe'],
    ];

    public function init()
    {
        $created = $modified = date('Y-m-d H:i:s');
        array_walk($this->records, function (&$record) use ($created, $modified) {
            $record += compact('created', 'modified');
        });
        parent::init();
    }
}
