<?php
/*
 * This file is part of the Andrew package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Andrew\Tests\Unit;

use PHPUnit_Framework_TestCase;
use Andrew\Callbacks\StaticCallbacks;
use Andrew\Tests\Stub;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
class StaticCallbacksTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Andrew\Exception\ClassException
     */
    public function testConstructorFailsIfNoClass()
    {
        new StaticCallbacks('meh');
    }

    public function testGetter()
    {
        $callbacks = new StaticCallbacks('Andrew\Tests\Stub');
        $getter = $callbacks->getter();
        assertInternalType('callable', $getter);
        assertSame('Private Static Var', $getter('private_static_var'));
    }

    public function testSetter()
    {
        $callbacks = new StaticCallbacks('Andrew\Tests\Stub');
        $setter = $callbacks->setter();
        assertInternalType('callable', $setter);
        assertSame('Private Static Var', Stub::staticGet());
        $setter('private_static_var', 'Edited Private Static Var');
        assertSame('Edited Private Static Var', Stub::staticGet());
    }

    public function testIsser()
    {
        $callbacks = new StaticCallbacks('Andrew\Tests\Stub');
        $isser = $callbacks->isser();
        assertInternalType('callable', $isser);
        assertTrue($isser('private_static_var'));
    }

    /**
     * @expectedException \Andrew\Exception\RuntimeException
     */
    public function testUnsetter()
    {
        $callbacks = new StaticCallbacks('Andrew\Tests\Stub');
        $callbacks->unsetter();
    }

    public function testCaller()
    {
        $callbacks = new StaticCallbacks('Andrew\Tests\Stub');
        $caller = $callbacks->caller();
        assertInternalType('callable', $caller);
        assertSame('Private Static Method Test!', $caller('privateStaticMethod', [' Test!']));
    }
}
