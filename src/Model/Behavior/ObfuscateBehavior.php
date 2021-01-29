<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior;

use ArrayObject;
use Cake\Database\Expression\Comparison;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use InvalidArgumentException;
use Muffin\Obfuscate\Model\Behavior\Strategy\StrategyInterface;
use RuntimeException;

/**
 * Class ObfuscateBehavior
 */
class ObfuscateBehavior extends Behavior
{
    /**
     * @inheritDoc
     */
    protected $_defaultConfig = [
        'strategy' => null,
        'implementedFinders' => [
            'obfuscated' => 'findObfuscated',
            'obfuscate' => 'findObfuscate',
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
    public function initialize(array $config): void
    {
        $this->verifyConfig();
    }

    /**
     * Verify config.
     *
     * @return void
     */
    public function verifyConfig(): void
    {
        $strategy = $this->getConfig('strategy');
        if (empty($strategy)) {
            throw new InvalidArgumentException('Missing required obfuscation strategy.');
        }

        if (!($strategy instanceof StrategyInterface)) {
            throw new InvalidArgumentException(
                'Strategy must implement ' . StrategyInterface::class
            );
        }

        parent::verifyConfig();
    }

    /**
     * Callback to obfuscate the record(s)' primary key returned after a save operation.
     *
     * @param \Cake\Event\EventInterface $event EventInterface.
     * @param \Cake\Datasource\EntityInterface $entity Entity.
     * @param \ArrayObject $options Options.
     * @return void
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        $pk = $this->_table->getPrimaryKey();
        if (is_array($pk)) {
            throw new RuntimeException('Composite primary keys are not supported.');
        }
        $entity->set($pk, $this->obfuscate($entity->{$pk}));
        $entity->setDirty($pk, false);
    }

    /**
     * Callback to set the `obfuscated` finder on all associations.
     *
     * @param \Cake\Event\EventInterface $event EventInterface.
     * @param \Cake\ORM\Query $query Query.
     * @param \ArrayObject $options Options.
     * @param bool $primary True if this is the primary table.
     * @return void
     */
    public function beforeFind(EventInterface $event, Query $query, ArrayObject $options, bool $primary)
    {
        if (empty($options['obfuscate']) || !$primary) {
            return;
        }

        $query->traverseExpressions(function ($expression) {
            $pk = $this->_table->getPrimaryKey();
            if (is_array($pk)) {
                throw new RuntimeException('Composite primary keys are not supported.');
            }

            if (
                $expression instanceof Comparison
                && in_array($expression->getField(), [$pk, $this->_table->aliasField($pk)])
            ) {
                $expression->setValue($this->elucidate($expression->getValue()));
            }

            return $expression;
        });

        foreach ($this->_table->associations() as $association) {
            if ($association->getTarget()->hasBehavior('Obfuscate') && $association->getFinder() === 'all') {
                $association->setFinder('obfuscate');
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
                $pk = $this->_table->getPrimaryKey();
                $row[$pk] = $this->obfuscate($row[$pk]);

                return $row;
            });
        });

        return $query;
    }

    /**
     * Proxy to the obfuscating strategy's `obfuscate()`.
     *
     * @param string|int $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str): string
    {
        return $this->strategy()->obfuscate($str);
    }

    /**
     * Proxy to the obfuscating strategy's `elucidate()`.
     *
     * @param int|string $str String to elucidate.
     * @return int
     */
    public function elucidate($str): int
    {
        return $this->strategy()->elucidate($str);
    }

    /**
     * Get the configured strategy.
     *
     * @return \Muffin\Obfuscate\Model\Behavior\Strategy\StrategyInterface
     */
    public function strategy(): StrategyInterface
    {
        return $this->getConfig('strategy');
    }
}
