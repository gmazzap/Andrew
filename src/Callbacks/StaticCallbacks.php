<?php
/*
 * This file is part of the Andrew package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Andrew\Callbacks;

use Andrew\Checker\Checker;
use Andrew\Exception\RuntimeException;
use Closure;
use ReflectionClass;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
final class StaticCallbacks implements CallbacksInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var object
     */
    private $object;

    /**
     * @var \Andrew\Checker\Checker
     */
    private $checker;

    /**
     * @param string                  $class
     * @param \Andrew\Checker\Checker $checker
     */
    public function __construct($class, Checker $checker = null)
    {
        is_null($checker) and $checker = new Checker();
        $checker->assertClass($class, __CLASS__.' expects a fully qualified class name.');
        $this->checker = $checker;
        $this->class = $class;
        $this->object = (new ReflectionClass($class))->newInstanceWithoutConstructor();
    }

    /**
     * @return \Closure
     */
    public function getter()
    {
        $checker = $this->checker;
        $getter = function ($var) use ($checker) {
            $checker->assertStaticProperty(
                get_called_class(),
                $var,
                'Undeclared static properties can not be retrieved.'
            );

            return static::$$var;
        };

        return Closure::bind($getter, $this->object, $this->class);
    }

    /**
     * @return \Closure
     */
    public function setter()
    {
        $checker = $this->checker;
        $setter = function ($var, $value) use ($checker) {
            $checker->assertStaticProperty(
                get_called_class(),
                $var,
                'Undeclared static properties can not be set.'
            );
            static::$$var = $value;
        };

        return Closure::bind($setter, $this->object, $this->class);
    }

    /**
     * @return \Closure
     */
    public function isser()
    {
        $checker = $this->checker;
        $isser = function ($var) use ($checker) {
            $checker->assertStaticProperty(
                get_called_class(),
                $var,
                'Undeclared static properties can not be checked.'
            );

            return isset(static::$$var);
        };

        return Closure::bind($isser, $this->object, $this->class);
    }

    /**
     * @return void
     */
    public function unsetter()
    {
        throw new RuntimeException('Is not possible to unset static properties.');
    }

    /**
     * @return \Closure
     */
    public function caller()
    {
        $checker = $this->checker;
        $caller = function ($method, $args) use ($checker) {
            $class = get_called_class();
            $checker->assertStaticMethod(
                $class,
                $method,
                'Undeclared static methods can not be called.'
            );

            return call_user_func_array([$class, $method], $args);
        };

        return Closure::bind($caller, $this->object, $this->class);
    }
}
