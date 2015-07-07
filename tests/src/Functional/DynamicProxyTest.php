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

use Andrew\Proxy;
use PHPUnit_Framework_TestCase;
use Andrew\Tests\Stub;

class DynamicProxyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNothing
     */
    public function testGetter()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertSame('Private Var', $proxy->private_var);
    }

    /**
     * @coversNothing
     */
    public function testGetterPublic()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertSame('Public Var', $proxy->public_var);
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testGetterFailsIfBadProperty()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->meh;
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testGetterFailsIfStaticProperty()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->public_static_var;
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testGetterFailsIfBadArgument()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->__get(true);
    }

    /**
     * @covers \Andrew\Proxy::__set
     */
    public function testSetter()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->private_var = 'Private Edited Var';

        assertSame('Private Edited Var', $stub->get());
    }

    /**
     * @coversNothing
     */
    public function testSetterPublic()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->public_var = 'Public Edited Var';

        assertSame('Public Edited Var', $stub->public_var);
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testSetterFailsIfBadProperty()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->meh = 'foo';
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testSetterFailsIfStaticProperty()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->public_static_var = 'foo';
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testSetterFailsIfBadArgument()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->__set([], '');
    }

    /**
     * @coversNothing
     */
    public function testIsser()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue(isset($proxy->private_var));
    }

    /**
     * @coversNothing
     */
    public function testIsserPublic()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue(isset($proxy->public_var));
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testIsserFailsIfBadVar()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue(isset($proxy->meh));
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testIsserFailsIfStaticVar()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue(isset($proxy->public_static_var));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testIsserFailsIfBadArgument()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->__isset(1);
    }

    /**
     * @coversNothing
     */
    public function testUnsetter()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue($stub->check());
        unset($proxy->private_var);
        assertFalse($stub->check());
    }

    /**
     * @coversNothing
     */
    public function testUnsetterPublic()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue(isset($stub->public_var));
        unset($proxy->public_var);
        assertFalse(isset($stub->public_var));
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testUnsetterFailsIfBadVar()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        unset($proxy->meh);
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @coversNothing
     */
    public function testUnsetterFailsIfStaticVar()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        unset($proxy->public_static_var);
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testUnsetterFailsIfBadArgument()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->__unset(true);
    }

    /**
     * @coversNothing
     */
    public function testCaller()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertSame('Private Dynamic Method', $proxy->privateDynamicMethod());
    }

    /**
     * @coversNothing
     */
    public function testCallerPublic()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertSame('Public Dynamic Method', $proxy->publicDynamicMethod());
    }

    /**
     * @expectedException \Andrew\Exception\MethodException
     * @coversNothing
     */
    public function testCallerFailsIfBadMethod()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->meh();
    }

    /**
     * @expectedException \Andrew\Exception\MethodException
     * @coversNothing
     */
    public function testCallerFailsIfStaticMethod()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->publicStaticMethod();
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @coversNothing
     */
    public function testCallerFailsIfBadArgument()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->__call([], []);
    }

    /**
     * @coversNothing
     */
    public function testInvoke()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        assertSame(['foo', 'bar', 'baz'], $proxy('foo', 'bar', 'baz'));
    }
}
