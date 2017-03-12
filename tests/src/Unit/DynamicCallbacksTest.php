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

use Andrew\Exception\ArgumentException;
use Andrew\Exception\ClassException;
use PHPUnit_Framework_TestCase;
use Andrew\Callbacks\DynamicCallbacks;
use Andrew\Tests\Stub;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
class DynamicCallbacksTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorFailsIfNoObject()
    {
        $this->expectException(ArgumentException::class);

        new DynamicCallbacks(__CLASS__);
    }

    public function testConstructorFailsIfStdClass()
    {
        $this->expectException(ClassException::class);

        new DynamicCallbacks(new \stdClass());
    }

    public function testGetter()
    {
        $callbacks = new DynamicCallbacks(new Stub());
        $getter = $callbacks->getter();
        assertInternalType('callable', $getter);
        assertSame('Private Var', $getter('private_var'));
    }

    public function testSetter()
    {
        $stub = new Stub();
        $callbacks = new DynamicCallbacks($stub);
        $setter = $callbacks->setter();
        assertInternalType('callable', $setter);
        assertSame('Private Var', $stub->get());
        $setter('private_var', 'Edited Private Var');
        assertSame('Edited Private Var', $stub->get());
    }

    public function testIsser()
    {
        $stub = new Stub();
        $callbacks = new DynamicCallbacks($stub);
        $isser = $callbacks->isser();
        assertInternalType('callable', $isser);
        assertTrue($isser('private_var'));
    }

    public function testUnsetter()
    {
        $stub = new Stub();
        $callbacks = new DynamicCallbacks($stub);
        $unsetter = $callbacks->unsetter();
        assertInternalType('callable', $unsetter);
        assertTrue($stub->check());
        $unsetter('private_var');
        assertFalse($stub->check());
    }

    public function testCaller()
    {
        $callbacks = new DynamicCallbacks(new Stub());
        $caller = $callbacks->caller();
        assertInternalType('callable', $caller);
        assertSame('Private Dynamic Method Test!', $caller('privateDynamicMethod', [' Test!']));
    }
}
