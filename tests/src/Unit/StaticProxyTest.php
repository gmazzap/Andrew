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

use Andrew\Exception\RuntimeException;
use Andrew\StaticProxy;
use PHPUnit_Framework_TestCase;
use Andrew\Callbacks\CallbacksInterface;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
class StaticProxyTest extends PHPUnit_Framework_TestCase
{

    /**
     * @param  string   $method
     * @param  callable $callback
     * @return \Andrew\Callbacks\CallbacksInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedCallbacks($method, callable $callback)
    {
        $callbacks = $this->getMockBuilder(CallbacksInterface::class)->getMock();
        $callbacks
            ->expects($this->once())
            ->method($method)
            ->willReturn($callback);

        return $callbacks;
    }

    public function testGet()
    {
        $function = function ($var) {
            assertSame('foo', $var);
        };

        $callbacks = $this->getMockedCallbacks('getter', $function);
        $proxy = new StaticProxy(__CLASS__, $callbacks);

        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__get('foo');
    }

    public function testSet()
    {
        $function = function ($var, $value) {
            assertSame('foo', $var);
            assertSame('bar', $value);
        };

        $callbacks = $this->getMockedCallbacks('setter', $function);
        $proxy = new StaticProxy(__CLASS__, $callbacks);

        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__set('foo', 'bar');
    }

    public function testIsset()
    {
        $function = function ($var) {
            assertSame('foo', $var);
        };

        $callbacks = $this->getMockedCallbacks('isser', $function);
        $proxy = new StaticProxy(__CLASS__, $callbacks);

        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__isset('foo');
    }
    
    public function testUnset()
    {
        $this->expectException(RuntimeException::class);

        /** @var \Andrew\Callbacks\CallbacksInterface $callbacks */
        $callbacks = $this->getMockBuilder(CallbacksInterface::class)->getMock();
        $proxy = new StaticProxy(__CLASS__, $callbacks);
        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__unset('foo');
    }

    public function testCall()
    {
        $function = function ($method, array $args) {
            assertSame('foo', $method);
            assertSame(['foo', 'bar'], $args);
        };

        $callbacks = $this->getMockedCallbacks('caller', $function);
        $proxy = new StaticProxy(__CLASS__, $callbacks);

        /** @noinspection ImplicitMagicMethodCallInspection */
        $proxy->__call('foo', ['foo', 'bar']);
    }
}
