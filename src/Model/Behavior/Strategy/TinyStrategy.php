<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

/**
 * Class TinyStrategy
 *
 * @deprecated Use Muffin\Obfuscate\Model\Behavior\Strategy\TuupolaStrategy instead
 */
class TinyStrategy implements StrategyInterface
{
    /**
     * TuupolaStrategy
     *
     * @var \Muffin\Obfuscate\Model\Behavior\Strategy\TuupolaStrategy
     */
    private TuupolaStrategy $tuupolaStrategy;

    /**
     * Constructor.
     *
     * @param string $set Random alpha-numeric set.
     */
    public function __construct(string $set)
    {
        $this->tuupolaStrategy = new TuupolaStrategy($set);
    }

    /**
     * @inheritDoc
     */
    public function obfuscate(string|int $str): string
    {
        return $this->tuupolaStrategy->obfuscate($str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate(string|int $str): int
    {
        return $this->tuupolaStrategy->elucidate($str);
    }
}
