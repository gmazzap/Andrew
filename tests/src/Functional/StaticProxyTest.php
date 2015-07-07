<?php
/*
 * This file is part of the Andrew package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Andrew\Tests\Functional;

use Andrew\StaticProxy;
use PHPUnit_Framework_TestCase;
use Andrew\Tests\Stub;

/**
 * @runTestsInSeparateProcesses
 */
class StaticProxyTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Andrew\StaticProxy\__get
     */
    public function testGetter()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');

        assertSame('Private Static Var', $proxy->private_static_var);
    }

    /**
     * @coversNothing
     */
    public function testGetterPublic()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');

        assertSame('Public Static Var', $proxy->public_static_var);
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testGetterFailsIfBadProperty()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->meh;
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testGetterFailsIfDynamicProperty()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->public_var;
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testGetterFailsIfBadArgument()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->__get(0);
    }

    /**
     * @covers \Andrew\StaticProxy\__set
     */
    public function testSetter()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');

        assertSame('Private Static Var', Stub::staticGet());
        $proxy->private_static_var = 'Edited Private Static Var';
        assertSame('Edited Private Static Var', Stub::staticGet());
    }

    /**
     * @coversNothing
     */
    public function testSetterPublic()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        assertSame('Public Static Var', Stub::$public_static_var);
        $proxy->public_static_var = 'Edited Public Static Var';
        assertSame('Edited Public Static Var', Stub::$public_static_var);
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testSetterFailsIfBadVar()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->meh = 'Meh';
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testSetterFailsIfDynamicVar()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->public_var = 'Meh';
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testSetterFailsIfBadArgument()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->__set(1, []);
    }

    /**
     * @covers \Andrew\StaticProxy\__isset
     */
    public function testIsser()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');

        assertTrue(isset($proxy->private_static_var));
    }

    /**
     * @coversNothing
     */
    public function testIsserPublic()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');

        assertTrue(isset($proxy->public_static_var));
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testIsserFailsIfBadVar()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $meh = isset($proxy->meh);
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testIsserFailsIfDynamicVar()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $meh = isset($proxy->public_var);
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testIsserFailsIfBadArgument()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->__isset(true);
    }

    /**
     * @expectedException \Andrew\Exception\RuntimeException
     * @covers \Andrew\StaticProxy\__unset
     */
    public function testUnsetterFails()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        unset($proxy->private_static_var);
    }

    /**
     * @expectedException \Andrew\Exception\RuntimeException
     * @coversNothing
     */
    public function testUnsetterFailsPublic()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        unset($proxy->public_static_var);
    }

    /**
     * @covers \Andrew\StaticProxy\__call
     */
    public function testCaller()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');

        assertSame('Private Static Method', $proxy->privateStaticMethod());
    }

    /**
     * @coversNothing
     */
    public function testCallerPublic()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');

        assertSame('Public Static Method', $proxy->publicStaticMethod());
    }

    /**
     * @expectedException \Andrew\Exception\MethodException
     * @coversNothing
     */
    public function testCallerFailsIfBadMethod()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->meh();
    }

    /**
     * @expectedException \Andrew\Exception\MethodException
     * @coversNothing
     */
    public function testCallerFailsIfDynamicMethod()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->publicDynamicMethod();
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testCallerFailsIfBadArgument()
    {
        $proxy = new StaticProxy('Andrew\Tests\Stub');
        $proxy->__call([], []);
    }
}
