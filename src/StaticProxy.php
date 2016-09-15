<?php
/*
 * This file is part of the Andrew package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Andrew;

use Andrew\Callbacks\CallbacksInterface;
use Andrew\Callbacks\StaticCallbacks;
use Andrew\Exception\RuntimeException;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
final class StaticProxy extends \stdClass
{
    /**
     * @var array
     */
    private $callbacks;

    /**
     * @param string                               $class
     * @param \Andrew\Callbacks\CallbacksInterface $callbacks
     */
    public function __construct($class, CallbacksInterface $callbacks = null)
    {
        $this->callbacks = $callbacks ?: new StaticCallbacks($class);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return call_user_func($this->callbacks->getter(), $name);
    }

    /**
     * @param  string $name
     * @param         $value
     * @return void
     */
    public function __set($name, $value)
    {
        call_user_func($this->callbacks->setter(), $name, $value);
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        return call_user_func($this->callbacks->isser(), $name);
    }

    /**
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        throw new RuntimeException('Is not possible to unset static properties.');
    }

    /**
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, array $arguments = [])
    {
        return call_user_func($this->callbacks->caller(), $name, $arguments);
    }
}
