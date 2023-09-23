<?php
declare(strict_types=1);

namespace Model\Behavior\Strategy;

use Cake\TestSuite\TestCase;
use Muffin\Obfuscate\Model\Behavior\Strategy\TuupolaStrategy;

class TuupolaStrategyTest extends TestCase
{
    /**
     * @var \Muffin\Obfuscate\Model\Behavior\Strategy\TuupolaStrategy
     */
    public TuupolaStrategy $strategy;

    public function setUp(): void
    {
        $this->strategy = new TuupolaStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B');
    }

    public function testObfuscate(): void
    {
        $this->assertEquals('S', $this->strategy->obfuscate(1));
    }

    public function testElucidate(): void
    {
        $this->assertEquals(1, $this->strategy->elucidate('S'));
    }
}
