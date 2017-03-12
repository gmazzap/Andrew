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
use Andrew\Exception\RuntimeException;
use Andrew\StaticProxy;
use PHPUnit_Framework_TestCase;
use Andrew\Tests\Stub;

/**
 * @runTestsInSeparateProcesses
 */
class StaticProxyTest extends PHPUnit_Framework_TestCase
{

    /**
     * @coversNothing
     */
    public function testGetter()
    {
        $proxy = new StaticProxy(Stub::class);

        assertSame('Private Static Var', $proxy->private_static_var);
    }

    /**
     * @coversNothing
     */
    public function testGetterPublic()
    {
        $proxy = new StaticProxy(Stub::class);

        assertSame('Public Static Var', $proxy->public_static_var);
    }

    /**
     * @coversNothing
     */
    public function testGetterFailsIfBadProperty()
    {
        $this->expectException(PropertyException::class);

        $proxy = new StaticProxy(Stub::class);
        $proxy->meh;
    }

    /**
     * @coversNothing
     */
    public function testGetterFailsIfDynamicProperty()
    {
        $this->expectException(PropertyException::class);

        $proxy = new StaticProxy(Stub::class);
        $proxy->public_var;
    }

    /**
     * @coversNothing
     */
    public function testGetterFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__get(0);
    }

    /**
     * @coversNothing
     */
    public function testSetter()
    {
        $proxy = new StaticProxy(Stub::class);

        assertSame('Private Static Var', Stub::staticGet());
        $proxy->private_static_var = 'Edited Private Static Var';
        assertSame('Edited Private Static Var', Stub::staticGet());
    }

    /**
     * @coversNothing
     */
    public function testSetterPublic()
    {
        $proxy = new StaticProxy(Stub::class);
        assertSame('Public Static Var', Stub::$public_static_var);
        $proxy->public_static_var = 'Edited Public Static Var';
        assertSame('Edited Public Static Var', Stub::$public_static_var);
    }

    /**
     * @coversNothing
     */
    public function testSetterFailsIfBadVar()
    {
        $this->expectException(PropertyException::class);

        $proxy = new StaticProxy(Stub::class);
        $proxy->meh = 'Meh';
    }

    /**
     * @coversNothing
     */
    public function testSetterFailsIfDynamicVar()
    {
        $this->expectException(PropertyException::class);

        $proxy = new StaticProxy(Stub::class);
        $proxy->public_var = 'Meh';
    }

    /**
     * @coversNothing
     */
    public function testSetterFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__set(1, []);
    }

    /**
     * @coversNothing
     */
    public function testIsser()
    {
        $proxy = new StaticProxy(Stub::class);

        assertTrue(isset($proxy->private_static_var));
    }

    /**
     * @coversNothing
     */
    public function testIsserPublic()
    {
        $proxy = new StaticProxy(Stub::class);

        assertTrue(isset($proxy->public_static_var));
    }

    /**
     * @coversNothing
     */
    public function testIsserFailsIfBadVar()
    {
        $this->expectException(PropertyException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection PhpExpressionResultUnusedInspection */
        isset($proxy->meh);
    }

    /**
     * @coversNothing
     */
    public function testIsserFailsIfDynamicVar()
    {
        $this->expectException(PropertyException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection PhpExpressionResultUnusedInspection */
        isset($proxy->public_var);
    }

    /**
     * @coversNothing
     */
    public function testIsserFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__isset(true);
    }

    /**
     * @coversNothing
     */
    public function testUnsetterFails()
    {
        $this->expectException(RuntimeException::class);

        $proxy = new StaticProxy(Stub::class);
        unset($proxy->private_static_var);
    }

    /**
     * @coversNothing
     */
    public function testUnsetterFailsPublic()
    {
        $this->expectException(RuntimeException::class);

        $proxy = new StaticProxy(Stub::class);
        unset($proxy->public_static_var);
    }

    /**
     * @coversNothing
     */
    public function testCaller()
    {
        $proxy = new StaticProxy(Stub::class);

        /** @noinspection PhpUndefinedMethodInspection */
        assertSame('Private Static Method', $proxy->privateStaticMethod());
    }

    /**
     * @coversNothing
     */
    public function testCallerPublic()
    {
        $proxy = new StaticProxy(Stub::class);

        /** @noinspection PhpUndefinedMethodInspection */
        assertSame('Public Static Method', $proxy->publicStaticMethod());
    }

    /**
     * @coversNothing
     */
    public function testCallerFailsIfBadMethod()
    {
        $this->expectException(MethodException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $proxy->meh();
    }

    /**
     * @coversNothing
     */
    public function testCallerFailsIfDynamicMethod()
    {
        $this->expectException(MethodException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $proxy->publicDynamicMethod();
    }

    /**
     * @coversNothing
     */
    public function testCallerFailsIfBadArgument()
    {
        $this->expectException(ArgumentException::class);

        $proxy = new StaticProxy(Stub::class);
        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__call([], []);
    }
}
