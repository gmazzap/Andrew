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

use Andrew\Exception\ArgumentException;
use Andrew\Exception\MethodException;
use Andrew\Exception\PropertyException;
use Andrew\Proxy;
use Andrew\Tests\EmptyStub;
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
     * @coversNothing
     */
    public function testGetterFailsIfBadProperty()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->meh;
    }

    /**
     * @coversNothing
     */
    public function testGetterFailsIfStaticProperty()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->public_static_var;
    }

    /**
     * @coversNothing
     */
    public function testGetterFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__get(true);
    }

    /**
     * @coversNothing
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
     * @coversNothing
     */
    public function testSetterFailsIfBadProperty()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->meh = 'foo';
    }

    /**
     * @coversNothing
     */
    public function testSetterFailsIfStaticProperty()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        $proxy->public_static_var = 'foo';
    }

    /**
     * @coversNothing
     */
    public function testSetterFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        /** @noinspection ImplicitMagicMethodCallInspection */
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
     * @coversNothing
     */
    public function testIsserFailsIfBadVar()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue(isset($proxy->meh));
    }

    /**
     * @coversNothing
     */
    public function testIsserFailsIfStaticVar()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);

        assertTrue(isset($proxy->public_static_var));
    }

    /**
     * @coversNothing
     */
    public function testIsserFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        /** @noinspection ImplicitMagicMethodCallInspection */
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

        /** @noinspection UnSafeIsSetOverArrayInspection */
        assertTrue(isset($stub->public_var));
        unset($proxy->public_var);
        /** @noinspection UnSafeIsSetOverArrayInspection */
        assertFalse(isset($stub->public_var));
    }

    /**
     * @coversNothing
     */
    public function testUnsetterFailsIfBadVar()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        unset($proxy->meh);
    }

    /**
     * @coversNothing
     */
    public function testUnsetterFailsIfStaticVar()
    {
        $this->expectException(PropertyException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        unset($proxy->public_static_var);
    }

    /**
     * @coversNothing
     */
    public function testUnsetterFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__unset(true);
    }

    /**
     * @coversNothing
     */
    public function testCaller()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        /** @noinspection PhpUndefinedMethodInspection */
        assertSame('Private Dynamic Method', $proxy->privateDynamicMethod());
    }

    /**
     * @coversNothing
     */
    public function testCallerPublic()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);

        /** @noinspection PhpUndefinedMethodInspection */
        assertSame('Public Dynamic Method', $proxy->publicDynamicMethod());
    }

    /**
     * @coversNothing
     */
    public function testCallerFailsIfBadMethod()
    {
        $this->expectException(MethodException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        /** @noinspection PhpUndefinedMethodInspection */
        $proxy->meh();
    }

    /**
     * @coversNothing
     */
    public function testCallerFailsIfStaticMethod()
    {
        $this->expectException(MethodException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        /** @noinspection PhpUndefinedMethodInspection */
        $proxy->publicStaticMethod();
    }

    /**
     * @coversNothing
     */
    public function testCallerFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $stub = new Stub();
        $proxy = new Proxy($stub);
        /** @noinspection ImplicitMagicMethodCallInspection */
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

    /**
     * @coversNothing
     */
    public function testToString()
    {
        $stub = new Stub();
        $proxy = new Proxy($stub);
        assertSame('I am the Stub', (string)$proxy);
    }

    /**
     * @coversNothing
     */
    public function testToStringFailsIfObjectCantString()
    {
        $this->expectException(MethodException::class);

        $stub = new EmptyStub();
        $proxy = new Proxy($stub);
        $proxy->__toString();
    }
}
