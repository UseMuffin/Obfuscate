<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use ZackKitzmiller\Tiny;

/**
 * Class TinyStrategy
 */
class TinyStrategy implements StrategyInterface
{
    /**
     * Random alpha-numeric set where each character must only be
     * used exactly once.
     *
     * @var string
     */
    protected string $_set;

    /**
     * Obfuscator.
     *
     * @var \ZackKitzmiller\Tiny
     */
    protected Tiny $_tiny;

    /**
     * Constructor.
     *
     * @param string $set Random alpha-numeric set.
     */
    public function __construct(string $set)
    {
        $this->_set = $set;
        $this->_tiny = new Tiny($set);
    }

    /**
     * @inheritDoc
     */
    public function obfuscate(string|int $str): string
    {
        return $this->_tiny->to((string)$str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate(string|int $str): int
    {
        return $this->_tiny->from($str);
    }
}
