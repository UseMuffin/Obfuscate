<?php
namespace Muffin\Obfuscate\Model\Behavior;

use ArrayObject;
use Cake\Core\Exception\Exception;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Muffin\Obfuscate\Model\Behavior\Strategy\StrategyInterface;

/**
 * Class ObfuscateBehavior
 *
 */
class ObfuscateBehavior extends Behavior
{

    /**
     * {@inheritdoc}
     */
    protected $_defaultConfig = [
        'strategy' => null,
        'implementedFinders' => [
            'obfuscated' => 'findObfuscated',
        ],
        'implementedMethods' => [
            'obfuscate' => 'obfuscate',
            'elucidate' => 'elucidate',
        ],
    ];

    /**
     * Initialize behavior
     *
     * @param array $config Behavior's configuration.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->verifyConfig();
    }

    /**
     * Verify config.
     *
     * @return void
     */
    public function verifyConfig()
    {
        if (!$strategy = $this->config('strategy')) {
            throw new Exception('Missing required obfuscation strategy.');
        }

        if (!($strategy instanceof StrategyInterface)) {
            throw new Exception(
                'Strategy must implement the `Muffin\Obfuscate\Model\Behavior\Strategy\StrategyInterface`'
            );
        }
    }

    /**
     * Callback to obfuscate the record(s)' primary key returned after a save operation.
     *
     * @param \Cake\ORM\Behavior\Event $event Event.
     * @param \Cake\ORM\Behavior\EntityInterface $entity Entity.
     * @param \ArrayObject $options Options.
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $pk = $this->_table->primaryKey();
        $entity->set($pk, $this->obfuscate($entity->{$pk}));
        $entity->dirty($pk, false);
    }

    /**
     * Callback to set the `obfuscated` finder on all associations.
     *
     * @param \Cake\ORM\Behavior\Event $event Event.
     * @param \Cake\ORM\Query $query Query.
     * @param \ArrayObject $options Options.
     * @param bool $primary True if this is the primary table.
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if (!$primary) {
            return;
        }

        $query->traverseExpressions(function ($expression) {
            if (method_exists($expression, 'getField')
                && $expression->getField() === $this->_table->primaryKey()
            ) {
                $expression->setValue($this->elucidate($expression->getValue()));
            }
            return $expression;
        });

        foreach ($this->_table->associations() as $association) {
            if ($association->target()->hasBehavior('Obfuscate') && 'all' === $association->finder()) {
                $association->finder('obfuscated');
            }
        }
    }

    /**
     * Custom finder to obfuscate the primary key in the result set.
     *
     * @param \Cake\ORM\Query $query Query.
     * @param array $options Options.
     * @return \Cake\ORM\Query
     */
    public function findObfuscated(Query $query, array $options)
    {
        $query->formatResults(function ($results) {
            return $results->map(function ($row) {
                $pk = $this->_table->primaryKey();
                $row[$pk] = $this->obfuscate($row[$pk]);
                return $row;
            });
        });
        return $query;
    }

    /**
     * Proxy to the obfuscating strategy's `obfuscate()`.
     *
     * @param string $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str)
    {
        return $this->strategy()->obfuscate($str);
    }

    /**
     * Proxy to the obfuscating strategy's `elucidate()`.
     *
     * @param string $str String to elucidate.
     * @return string
     */
    public function elucidate($str)
    {
        return $this->strategy()->elucidate($str);
    }

    /**
     * Get the configured strategy.
     *
     * @return \Muffin\Obfuscate\Model\Behavior\ObfuscateStrategy\StrategyInterface
     */
    public function strategy()
    {
        return $this->config('strategy');
    }
}
