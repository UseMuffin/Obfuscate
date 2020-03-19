<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Muffin\Obfuscate\Model\Behavior\Strategy\TinyStrategy;
use Muffin\Obfuscate\Model\Behavior\Strategy\UuidStrategy;

class ObfuscateBehaviorTest extends TestCase
{
    /**
     * @var \Cake\ORM\Table;
     */
    public $Articles;

    /**
     * @var \Cake\ORM\Table;
     */
    public $Authors;

    /**
     * @var \Cake\ORM\Table;
     */
    public $Comments;

    /**
     * @var \Cake\ORM\Table;
     */
    public $Tags;

    /**
     * @var \Muffin\Obfuscate\Model\Behavior\ObfuscateBehavior
     */
    public $Obfuscate;

    public $fixtures = [
        'plugin.Muffin/Obfuscate.Articles',
        'plugin.Muffin/Obfuscate.Authors',
        'plugin.Muffin/Obfuscate.Comments',
        'plugin.Muffin/Obfuscate.Tags',
        'plugin.Muffin/Obfuscate.ArticlesTags',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->Authors = TableRegistry::get('Muffin/Obfuscate.Authors', ['table' => 'obfuscate_authors']);
        $this->Authors->addBehavior('Muffin/Obfuscate.Obfuscate', [
            'strategy' => new UuidStrategy($this->Authors),
        ]);

        $this->Comments = TableRegistry::get('Muffin/Obfuscate.Comments', ['table' => 'obfuscate_comments']);

        $this->Tags = TableRegistry::get('Muffin/Obfuscate.Tags', ['table' => 'obfuscate_tags']);
        $this->Tags->addBehavior('Muffin/Obfuscate.Obfuscate', [
            'strategy' => new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B'),
        ]);

        $this->Articles = TableRegistry::get('Muffin/Obfuscate.Articles', ['table' => 'obfuscate_articles']);
        $this->Articles->addBehavior('Muffin/Obfuscate.Obfuscate', ['strategy' => new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B')]);
        $this->Articles->hasMany('Muffin/Obfuscate.Comments');
        $this->Articles->belongsTo('Muffin/Obfuscate.Authors');
        $this->Articles->belongsToMany('Muffin/Obfuscate.Tags', [
            'foreignKey' => 'obfuscate_article_id',
            'joinTable' => 'obfuscate_articles_tags',
            'through' => TableRegistry::get('Muffin/Obfuscate.ArticlesTags', ['table' => 'obfuscate_articles_tags']),
        ]);

        $this->Obfuscate = $this->Articles->getBehavior('Obfuscate');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        TableRegistry::clear();
    }

    public function testVerifyConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required obfuscation strategy');

        $this->Articles->removeBehavior('Obfuscate');
        $this->Articles->addBehavior('Muffin/Obfuscate.Obfuscate');
    }

    public function testAfterSave(): void
    {
        $entity = new Entity(['id' => 5, 'title' => 'foo']);
        $this->Articles->save($entity);
        $this->assertEquals('E', $entity['id']);
        $this->assertFalse($entity->isDirty('id'));
    }

    /**
     * Make sure primary keys in returned result set are obfuscated when using
     * the `obfuscate` custom finder.
     */
    public function testFindObfuscate(): void
    {
        $result = $this->Articles->find('obfuscate')->contain([
            $this->Authors->getAlias(),
            $this->Comments->getAlias(),
            $this->Tags->getAlias(),
        ])->first();

        $this->assertEquals('f1f88079-ec15-4863-ad41-7e85cfa98f3d', $result['author']['id']);
        $this->assertEquals('S', $result['tags'][0]['id']);
        $this->assertEquals(1, $result['comments'][0]['id']);
        $this->assertEquals(2, $result['comments'][1]['id']);
    }

    /**
     * Make sure primary keys in the returned result set are NOT obfuscated
     * when using default find.
     */
    public function testFindWithoutObfuscate(): void
    {
        $result = $this->Articles->find()->contain([
            $this->Authors->getAlias(),
            $this->Comments->getAlias(),
            $this->Tags->getAlias(),
        ])->first();

        $this->assertEquals('1', $result['author']['id']);
        $this->assertEquals('1', $result['tags'][0]['id']);
        $this->assertEquals(1, $result['comments'][0]['id']);
        $this->assertEquals(2, $result['comments'][1]['id']);
    }

    /**
     * Make sure we can search for records using obfuscated primary key when
     * using the `obfuscated` custom finder.
     */
    public function testFindObfuscated(): void
    {
        $results = $this->Articles->find('obfuscated')
            ->where(['id' => 'S'])
            ->toArray();
        $this->assertEquals('1', $results[0]['id']);

        $results = $this->Authors->find('obfuscated')
            ->where(['id' => 'f1f88079-ec15-4863-ad41-7e85cfa98f3d'])
            ->toArray();
        $this->assertEquals('1', $results[0]['id']);
    }

    /**
     * Make sure we can search for records using non-obfuscated primary key
     * when using default find.
     */
    public function testFindWithoutObfuscated(): void
    {
        $results = $this->Articles->find()
            ->where(['id' => '1'])
            ->toArray();
        $this->assertEquals('1', $results[0]['id']);
    }

    public function testObfuscate(): void
    {
        $this->assertEquals('S', $this->Articles->behaviors()->call('obfuscate', [1]));
    }

    public function testElucidate(): void
    {
        $this->assertEquals(1, $this->Articles->behaviors()->call('elucidate', ['S']));
    }
}
