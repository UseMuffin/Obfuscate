<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior\Strategy;

use Cake\TestSuite\TestCase;
use Muffin\Obfuscate\Model\Behavior\Strategy\OptimusStrategy;

class OptimusStrategyTest extends TestCase
{
    public function setUp(): void
    {
        $this->strategy = new OptimusStrategy(2123809381, 1885413229, 146808189);
    }

    public function testObfuscate(): void
    {
        $this->assertEquals(1985404696, $this->strategy->obfuscate(1));
    }

    public function testElucidate(): void
    {
        $this->assertEquals(1, $this->strategy->elucidate(1985404696));
    }
}
