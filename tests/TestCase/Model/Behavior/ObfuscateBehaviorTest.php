<?php
namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Muffin\Obfuscate\Model\Behavior\Strategy\TinyStrategy;

class ObfuscateBehaviorTest extends TestCase
{
    /**
     * @var \Cake\ORM\Table;
     */
    public $Articles;
    public $Authors;
    public $Comments;
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

    public function setUp()
    {
        parent::setUp();

        $strategy = new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B');

        $this->Authors = TableRegistry::get('Muffin/Obfuscate.Authors', ['table' => 'obfuscate_authors']);
        $this->Authors->addBehavior('Muffin/Obfuscate.Obfuscate', compact('strategy'));

        $this->Comments = TableRegistry::get('Muffin/Obfuscate.Comments', ['table' => 'obfuscate_comments']);

        $this->Tags = TableRegistry::get('Muffin/Obfuscate.Tags', ['table' => 'obfuscate_tags']);
        $this->Tags->addBehavior('Muffin/Obfuscate.Obfuscate', compact('strategy'));

        $this->Articles = TableRegistry::get('Muffin/Obfuscate.Articles', ['table' => 'obfuscate_articles']);
        $this->Articles->addBehavior('Muffin/Obfuscate.Obfuscate', ['strategy' => new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B')]);
        $this->Articles->hasMany('Muffin/Obfuscate.Comments');
        $this->Articles->belongsTo('Muffin/Obfuscate.Authors');
        $this->Articles->belongsToMany('Muffin/Obfuscate.Tags', [
            'foreignKey' => 'obfuscate_article_id',
            'joinTable' => 'obfuscate_articles_tags',
            'through' => TableRegistry::get('Muffin/Obfuscate.ArticlesTags', ['table' => 'obfuscate_articles_tags'])
        ]);


        $this->Obfuscate = $this->Articles->behaviors()->Obfuscate;
    }

    public function tearDown()
    {
        parent::tearDown();
        TableRegistry::clear();
    }

    /**
     * @expectedException \Cake\Core\Exception\Exception
     */
    public function testVerifyConfig()
    {
        $this->Articles->removeBehavior('Obfuscate');
        $this->Articles->addBehavior('Muffin/Obfuscate.Obfuscate');
    }

    public function testAfterSave()
    {
        $entity = new Entity(['id' => 5, 'title' => 'foo']);
        $this->Articles->save($entity);
        $this->assertEquals('E', $entity['id']);
        $this->assertFalse($entity->dirty('id'));
    }

    public function testBeforeFind()
    {
        $result = $this->Articles->find()->contain([
            $this->Authors->alias(),
            $this->Comments->alias(),
            $this->Tags->alias(),
        ])->first();

        $this->assertEquals('S', $result['author']['id']);
        $this->assertEquals('S', $result['tags'][0]['id']);
        $this->assertEquals(1, $result['comments'][0]['id']);
        $this->assertEquals(2, $result['comments'][1]['id']);
    }

    public function testFindObfuscated()
    {
        $results = $this->Articles->find('obfuscated')->toArray();
        $this->assertEquals('S', $results[0]['id']);
        $this->assertEquals('X', $results[1]['id']);
    }

    public function testObfuscate()
    {
        $this->assertEquals('S', $this->Articles->obfuscate(1));
    }

    public function testElucidate()
    {
        $this->assertEquals(1, $this->Articles->elucidate('S'));
    }

    public function testStrategy()
    {
    }
}
