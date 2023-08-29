<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use Cake\ORM\Table;

/**
 * Class UuidStrategy
 */
class UuidStrategy implements StrategyInterface
{
    /**
     * UUID field to use.
     *
     * @var string
     */
    protected string $_field;

    /**
     * Table using this strategy.
     *
     * @var \Cake\ORM\Table
     */
    protected Table $_table;

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table Instance of the table using the strategy.
     * @param string $field Name of the UUID field on the table.
     */
    public function __construct(Table $table, string $field = 'uuid')
    {
        $this->_table = $table;
        $this->_field = $field;
    }

    /**
     * @inheritDoc
     */
    public function obfuscate(string|int $str): string
    {
        /** @psalm-suppress InvalidArrayOffset */
        $record = $this->_table
            ->find()
            ->where([$this->_table->getPrimaryKey() => $str])
            ->select([$this->_field])
            ->firstOrFail();

        return $record->{$this->_field};
    }

    /**
     * @inheritDoc
     */
    public function elucidate(string|int $str): int
    {
        $pk = $this->_table->getPrimaryKey();

        $record = $this->_table
            ->find()
            ->where([$this->_field => $str])
            ->select([$pk])
            ->firstOrFail();

        return $record->$pk;
    }
}
