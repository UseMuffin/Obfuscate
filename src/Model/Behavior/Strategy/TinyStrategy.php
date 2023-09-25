<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

/**
 * Class TinyStrategy
 *
 * @deprecated Use Muffin\Obfuscate\Model\Behavior\Strategy\Base62Strategy instead
 */
class TinyStrategy implements StrategyInterface
{
    /**
     * TuupolaStrategy
     *
     * @var \Muffin\Obfuscate\Model\Behavior\Strategy\Base62Strategy
     */
    private Base62Strategy $base62Strategy;

    /**
     * Constructor.
     *
     * @param string $set Random alpha-numeric set.
     */
    public function __construct(string $set)
    {
        $this->base62Strategy = new Base62Strategy($set);
    }

    /**
     * @inheritDoc
     */
    public function obfuscate(string|int $str): string
    {
        return $this->base62Strategy->obfuscate($str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate(string|int $str): int
    {
        return $this->base62Strategy->elucidate($str);
    }
}
