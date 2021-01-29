<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

/**
 * Interface StrategyInterface
 */
interface StrategyInterface
{
    /**
     * Obfuscates a given integer/string.
     *
     * @param int|string $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str): string;

    /**
     * Elucidates (de-obfuscates) a given string.
     *
     * @param int|string $str String to elucidate.
     * @return int
     */
    public function elucidate($str): int;
}
