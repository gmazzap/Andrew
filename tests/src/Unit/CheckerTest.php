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
use Andrew\Exception\MethodException;
use Andrew\Exception\PropertyException;
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
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        assertNull($checker->assertObject($this, ''));
    }

    public function testAssertObjectException()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertObject('meh!', 'WTF?!?');
    }

    public function testAssertString()
    {
        $checker = new Checker();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        assertNull($checker->assertString('ok', ''));
    }

    public function testAssertStringException()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertString(1, 'WTF?!?');
    }

    public function testAssertClass()
    {
        $checker = new Checker();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        assertNull($checker->assertClass(__CLASS__, ''));
    }

    public function testAssertClassExceptionIfNoString()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertClass($this, 'WTF?!?');
    }

    public function testAssertClassExceptionIfNoClass()
    {
        $this->expectException(ClassException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertClass('meh!', 'WTF?!?');
    }

    public function testAssertMethod()
    {
        $checker = new Checker();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        assertNull($checker->assertMethod($this, __FUNCTION__, ''));
    }

    public function testAssertMethodExceptionIfNoString()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertMethod($this, [], 'WTF?!?');
    }

    public function testAssertMethodExceptionIfNoObject()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertMethod(__CLASS__, __FUNCTION__, 'WTF?!?');
    }

    public function testAssertMethodExceptionIfNotMethod()
    {
        $this->expectException(MethodException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertMethod($this, 'meh!', 'WTF?!?');
    }

    public function testAssertMethodExceptionIfStaticMethod()
    {
        $this->expectException(MethodException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertMethod($this, 'stubMethod', 'WTF?!?');
    }

    public function testAssertStaticMethod()
    {
        $checker = new Checker();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        assertNull($checker->assertStaticMethod(__CLASS__, 'stubMethod', ''));
    }

    public function testAssertStaticMethodExceptionIfNoString()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticMethod(__CLASS__, [], 'WTF?!?');
    }

    public function testAssertMethodExceptionIfNoClass()
    {
        $this->expectException(ClassException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticMethod('meh!', 'privateStaticMethod', 'WTF?!?');
    }

    public function testAssertMethodExceptionIfNoMethod()
    {
        $this->expectException(MethodException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticMethod(__CLASS__, 'meh!', 'WTF?!?');
    }

    public function testAssertMethodExceptionIfDynamicMethod()
    {
        $this->expectException(MethodException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticMethod(__CLASS__, __FUNCTION__, 'WTF?!?');
    }

    public function testAssertProperty()
    {
        $checker = new Checker();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        assertNull($checker->assertProperty($this, 'var', ''));
    }

    public function testAssertPropertyExceptionIfNoString()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertProperty($this, 0, 'WTF?!?');
    }

    public function testAssertPropertyExceptionIfNoObject()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertProperty(__CLASS__, 'var', 'WTF?!?');
    }

    public function testAssertPropertyExceptionIfNoProperty()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertProperty($this, 'meh!', 'WTF?!?');
    }

    public function testAssertPropertyExceptionIfStaticProperty()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertProperty($this, 'staticvar', 'WTF?!?');
    }

    public function testAssertStaticProperty()
    {
        $checker = new Checker();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        assertNull($checker->assertStaticProperty(__CLASS__, 'staticvar', ''));
    }

    public function testAssertStaticPropertyExceptionIfNoString()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticProperty(__CLASS__, 0, 'WTF?!?');
    }

    public function testAssertStaticPropertyExceptionIfNoClass()
    {
        $this->expectException(ClassException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticProperty('meh!', 'staticvar', 'WTF?!?');
    }

    public function testAssertStaticPropertyExceptionIfNoProperty()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticProperty(__CLASS__, 'meh!', 'WTF?!?');
    }

    public function testAssertPropertyExceptionIfDynamicProperty()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage('WTF?!?');

        $checker = new Checker();
        $checker->assertStaticProperty(__CLASS__, 'var', 'WTF?!?');
    }
}
