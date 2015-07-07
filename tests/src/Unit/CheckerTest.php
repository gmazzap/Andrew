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
use Andrew\Checker\Checker;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
class CheckerTest extends PHPUnit_Framework_TestCase
{
    private static $staticvar;
    private $var;

    private static function stubMethod()
    {
    }

    public function testAssertObject()
    {
        $checker = new Checker();
        assertNull($checker->assertObject($this, ''));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertObjectException()
    {
        $checker = new Checker();
        $checker->assertObject('meh!', 'WTF?!?');
    }

    public function testAssertString()
    {
        $checker = new Checker();
        assertNull($checker->assertString('ok', ''));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertStringException()
    {
        $checker = new Checker();
        $checker->assertString(1, 'WTF?!?');
    }

    public function testAssertClass()
    {
        $checker = new Checker();
        assertNull($checker->assertClass(__CLASS__, ''));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertClassExceptionIfNoString()
    {
        $checker = new Checker();
        $checker->assertClass($this, 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\ClassException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertClassExceptionIfNoClass()
    {
        $checker = new Checker();
        $checker->assertClass('meh!', 'WTF?!?');
    }

    public function testAssertMethod()
    {
        $checker = new Checker();
        assertNull($checker->assertMethod($this, __FUNCTION__, ''));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertMethodExceptionIfNoString()
    {
        $checker = new Checker();
        $checker->assertMethod($this, [], 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertMethodExceptionIfNoObject()
    {
        $checker = new Checker();
        $checker->assertMethod(__CLASS__, __FUNCTION__, 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\MethodException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertMethodExceptionIfNotMethod()
    {
        $checker = new Checker();
        $checker->assertMethod($this, 'meh!', 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\MethodException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertMethodExceptionIfStaticMethod()
    {
        $checker = new Checker();
        $checker->assertMethod($this, 'stubMethod', 'WTF?!?');
    }

    public function testAssertStaticMethod()
    {
        $checker = new Checker();
        assertNull($checker->assertStaticMethod(__CLASS__, 'stubMethod', ''));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertStaticMethodExceptionIfNoString()
    {
        $checker = new Checker();
        $checker->assertStaticMethod(__CLASS__, [], 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\ClassException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertMethodExceptionIfNoClass()
    {
        $checker = new Checker();
        $checker->assertStaticMethod('meh!', 'privateStaticMethod', 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\MethodException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertMethodExceptionIfDynamicMethod()
    {
        $checker = new Checker();
        $checker->assertStaticMethod(__CLASS__, __FUNCTION__, 'WTF?!?');
    }

    public function testAssertProperty()
    {
        $checker = new Checker();
        assertNull($checker->assertProperty($this, 'var', ''));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertPropertyExceptionIfNoString()
    {
        $checker = new Checker();
        $checker->assertProperty($this, 0, 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertPropertyExceptionIfNoObject()
    {
        $checker = new Checker();
        $checker->assertProperty(__CLASS__, 'var', 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertPropertyExceptionIfNoProperty()
    {
        $checker = new Checker();
        $checker->assertProperty($this, 'meh!', 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertPropertyExceptionIfStaticProperty()
    {
        $checker = new Checker();
        $checker->assertProperty($this, 'staticvar', 'WTF?!?');
    }

    public function testAssertStaticProperty()
    {
        $checker = new Checker();
        assertNull($checker->assertStaticProperty(__CLASS__, 'staticvar', ''));
    }

    /**
     * @expectedException \Andrew\Exception\ArgumentException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertStaticPropertyExceptionIfNoString()
    {
        $checker = new Checker();
        $checker->assertStaticProperty(__CLASS__, 0, 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\ClassException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertStaticPropertyExceptionIfNoClass()
    {
        $checker = new Checker();
        $checker->assertStaticProperty('meh!', 'staticvar', 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertStaticPropertyExceptionIfNoProperty()
    {
        $checker = new Checker();
        $checker->assertStaticProperty(__CLASS__, 'meh!', 'WTF?!?');
    }

    /**
     * @expectedException \Andrew\Exception\PropertyException
     * @expectedExceptionMessage WTF?!?
     */
    public function testAssertPropertyExceptionIfDynamicProperty()
    {
        $checker = new Checker();
        $checker->assertStaticProperty(__CLASS__, 'var', 'WTF?!?');
    }
}
