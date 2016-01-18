<?php
namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior\Strategy;

use Muffin\Obfuscate\Model\Behavior\Strategy\HashidStrategy;

class HashidStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->strategy = new HashidStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B');
    }

    public function testObfuscate()
    {
        $result = $this->assertEquals('k8', $this->strategy->obfuscate(1));
    }

    public function testElucidate()
    {
        $this->assertEquals(1, $this->strategy->elucidate('k8'));
    }

}
