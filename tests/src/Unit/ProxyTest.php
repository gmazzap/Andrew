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

use Andrew\Proxy;
use PHPUnit_Framework_TestCase;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
class ProxyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param                                       $method
     * @param  callable                             $callback
     * @return \Andrew\Callbacks\CallbacksInterface
     */
    private function getMockedCallbacks($method, callable $callback)
    {
        $callbacks = $this->getMockBuilder('Andrew\Callbacks\CallbacksInterface')->getMock();
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
        $proxy = new Proxy($this, $callbacks);

        $proxy->__get('foo');
    }

    public function testSet()
    {
        $function = function ($var, $value) {
            assertSame('foo', $var);
            assertSame('bar', $value);
        };

        $callbacks = $this->getMockedCallbacks('setter', $function);
        $proxy = new Proxy($this, $callbacks);

        $proxy->__set('foo', 'bar');
    }

    public function testIsset()
    {
        $function = function ($var) {
            assertSame('foo', $var);
        };

        $callbacks = $this->getMockedCallbacks('isser', $function);
        $proxy = new Proxy($this, $callbacks);

        $proxy->__isset('foo');
    }

    public function testUnset()
    {
        $function = function ($var) {
            assertSame('foo', $var);
        };

        $callbacks = $this->getMockedCallbacks('unsetter', $function);
        $proxy = new Proxy($this, $callbacks);

        $proxy->__unset('foo');
    }

    public function testCall()
    {
        $function = function ($method, array $args) {
            assertSame('foo', $method);
            assertSame(['foo', 'bar'], $args);
        };

        $callbacks = $this->getMockedCallbacks('caller', $function);
        $proxy = new Proxy($this, $callbacks);

        $proxy->__call('foo', ['foo', 'bar']);
    }

    public function testInvoke()
    {
        $function = function ($method, array $args) {
            assertSame('__invoke', $method);
            assertSame(['foo', 'bar'], $args);
        };

        $callbacks = $this->getMockedCallbacks('caller', $function);
        $proxy = new Proxy($this, $callbacks);
        $proxy('foo', 'bar');
    }
}
