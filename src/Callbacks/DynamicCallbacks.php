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
use Closure;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
final class DynamicCallbacks implements CallbacksInterface
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
     * @param object                  $object
     * @param \Andrew\Checker\Checker $checker
     */
    public function __construct($object, Checker $checker = null)
    {
        is_null($checker) and $checker = new Checker();
        $checker->assertObject($object, __CLASS__.' expects an object.');
        $this->checker = $checker;
        $this->object = $object;
        $this->class = get_class($object);
    }

    /**
     * @return \Closure
     */
    public function getter()
    {
        $checker = $this->checker;
        $getter = function ($var) use ($checker) {
            $checker->assertProperty($this, $var, 'Undeclared properties can not be retrieved.');

            return $this->$var;
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
            $checker->assertProperty($this, $var, 'Undeclared properties can not be set.');
            $this->$var = $value;
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
            $checker->assertProperty($this, $var, 'Undeclared properties can not be checked.');

            return isset($this->$var);
        };

        return Closure::bind($isser, $this->object, $this->class);
    }

    /**
     * @return \Closure
     */
    public function unsetter()
    {
        $checker = $this->checker;
        $unsetter = function ($var) use ($checker) {
            $checker->assertProperty($this, $var, 'Undeclared properties can not be unset.');
            unset($this->$var);
        };

        return Closure::bind($unsetter, $this->object, $this->class);
    }

    /**
     * @return \Closure
     */
    public function caller()
    {
        $checker = $this->checker;
        $caller = function ($method, $args) use ($checker) {
            $checker->assertMethod($this, $method, 'Undeclared methods can not be called.');

            return call_user_func_array([$this, $method], $args);
        };

        return Closure::bind($caller, $this->object, $this->class);
    }
}
