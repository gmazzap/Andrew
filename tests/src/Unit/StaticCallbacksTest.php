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

use Andrew\Exception\ClassException;
use Andrew\Exception\RuntimeException;
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
    public function testConstructorFailsIfNoClass()
    {
        $this->expectException(ClassException::class);

        new StaticCallbacks('meh');
    }

    public function testConstructorFailsIfStdClass()
    {
        $this->expectException(ClassException::class);

        new StaticCallbacks(\stdClass::class);
    }

    public function testConstructorFailsIfNativeClass()
    {
        $this->expectException(ClassException::class);

        new StaticCallbacks(\ArrayObject::class);
    }

    public function testGetter()
    {
        $callbacks = new StaticCallbacks(Stub::class);
        $getter = $callbacks->getter();
        assertInternalType('callable', $getter);
        assertSame('Private Static Var', $getter('private_static_var'));
    }

    public function testSetter()
    {
        $callbacks = new StaticCallbacks(Stub::class);
        $setter = $callbacks->setter();
        assertInternalType('callable', $setter);
        assertSame('Private Static Var', Stub::staticGet());
        $setter('private_static_var', 'Edited Private Static Var');
        assertSame('Edited Private Static Var', Stub::staticGet());
    }

    public function testIsser()
    {
        $callbacks = new StaticCallbacks(Stub::class);
        $isser = $callbacks->isser();
        assertInternalType('callable', $isser);
        assertTrue($isser('private_static_var'));
    }

    public function testUnsetter()
    {
        $this->expectException(RuntimeException::class);

        $callbacks = new StaticCallbacks(Stub::class);
        $callbacks->unsetter();
    }

    public function testCaller()
    {
        $callbacks = new StaticCallbacks(Stub::class);
        $caller = $callbacks->caller();
        assertInternalType('callable', $caller);
        assertSame('Private Static Method Test!', $caller('privateStaticMethod', [' Test!']));
    }
}
