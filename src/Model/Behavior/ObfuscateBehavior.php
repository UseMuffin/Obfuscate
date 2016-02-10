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
            'obfuscate' => 'findObfuscate'
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

        parent::verifyConfig();
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
        if (empty($options['obfuscate']) || !$primary) {
            return;
        }

        $query->traverseExpressions(function ($expression) {
            $pk = $this->_table->primaryKey();
            if (method_exists($expression, 'getField')
                && in_array($expression->getField(), [$pk, $this->_table->aliasField($pk)])
            ) {
                $expression->setValue($this->elucidate($expression->getValue()));
            }

            return $expression;
        });

        foreach ($this->_table->associations() as $association) {
            if ($association->target()->hasBehavior('Obfuscate') && 'all' === $association->finder()) {
                $association->finder('obfuscate');
            }
        }
    }

    /**
     * Custom finder to search for records using an obfuscated primary key.
     *
     * @param \Cake\ORM\Query $query Query.
     * @param array $options Options.
     * @return \Cake\ORM\Query
     */
    public function findObfuscated(Query $query, array $options)
    {
        return $query->applyOptions(['obfuscate' => true]);
    }

    /**
     * Custom finder that obfuscates primary keys in returned result set.
     *
     * @param \Cake\ORM\Query $query Query.
     * @param array $options Options.
     * @return \Cake\ORM\Query
     */
    public function findObfuscate(Query $query, array $options)
    {
        $query->applyOptions(['obfuscate' => true]);

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
