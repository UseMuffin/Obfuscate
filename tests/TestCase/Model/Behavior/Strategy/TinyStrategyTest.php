<?php
namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior\Strategy;

use Muffin\Obfuscate\Model\Behavior\Strategy\TinyStrategy;

class TinyStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->strategy = new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B');
    }

    public function testObfuscate()
    {
        $this->assertEquals('S', $this->strategy->obfuscate(1));
    }

    public function testElucidate()
    {
        $this->assertEquals(1, $this->strategy->elucidate('S'));
    }

}
