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
use Andrew\Callbacks\DynamicCallbacks;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
final class Proxy extends \stdClass
{
    /**
     * @var array
     */
    private $callbacks;

    /**
     * @param object                                    $object
     * @param \Andrew\Callbacks\CallbacksInterface|null $callbacks
     */
    public function __construct($object, CallbacksInterface $callbacks = null)
    {
        $this->callbacks = $callbacks ?: new DynamicCallbacks($object);
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
        call_user_func($this->callbacks->unsetter(), $name);
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

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->__call('__invoke', func_get_args());
    }
}
