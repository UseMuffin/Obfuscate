<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use ZackKitzmiller\Tiny;

/**
 * Class TinyStrategy
 *
 */
class TinyStrategy implements StrategyInterface
{
    /**
     * Random alpha-numeric set where each character must only be
     * used exactly once.
     *
     * @var string
     */
    protected $_set;

    /**
     * Obfuscator.
     *
     * @var \ZackKitzmiller\Tiny
     */
    protected $_tiny;

    /**
     * Constructor.
     *
     * @param string $set Random alpha-numeric set.
     */
    public function __construct($set)
    {
        $this->_set = $set;
        $this->_tiny = new Tiny($set);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str)
    {
        return $this->_tiny->to($str);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to elucidate.
     * @return string
     */
    public function elucidate($str)
    {
        return $this->_tiny->from($str);
    }
}
