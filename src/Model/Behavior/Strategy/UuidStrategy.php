<?php
namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use Cake\ORM\Table;

/**
 * Class UuidStrategy
 *
 */
class UuidStrategy implements StrategyInterface
{

    /**
     * UUID field to use.
     *
     * @var string
     */
    protected $_field;

    /**
     * Table using this strategy.
     *
     * @var Table
     */
    protected $_table;

    /**
     * Constructor.
     *
     * @param Table $table Instance of the table using the strategy.
     * @param string $field Name of the UUID field on the table.
     */
    public function __construct($table, $field = 'uuid')
    {
        $this->_table = $table;
        $this->_field = $field;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str)
    {
        $record = $this->_table
            ->find()
            ->where([$this->_table->getPrimaryKey() => $str])
            ->select([$this->_field])
            ->firstOrFail();

        return $record->{$this->_field};
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to elucidate.
     * @return string
     */
    public function elucidate($str)
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
