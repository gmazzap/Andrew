<?php
/*
 * This file is part of the Andrew package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Andrew\Checker;

use Andrew\Exception\ArgumentException;
use Andrew\Exception\ClassException;
use Andrew\Exception\MethodException;
use Andrew\Exception\PropertyException;
use ReflectionProperty;
use ReflectionMethod;
use ReflectionException;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
final class Checker
{
    /**
     * @var array
     */
    private $checked = [
        'var'        => [],
        'staticvar'  => [],
        'func'       => [],
        'staticfunc' => [],
    ];

    /**
     * @param object $object
     * @param string $message
     */
    public function assertObject($object, $message)
    {
        if (! is_object($object)) {
            throw new ArgumentException($message);
        }
    }

    /**
     * @param string $string
     * @param string $message
     */
    public function assertString($string, $message)
    {
        if (! is_string($string)) {
            throw new ArgumentException($message);
        }
    }

    /**
     * @param string $class
     * @param string $message
     */
    public function assertClass($class, $message)
    {
        $this->assertString($class, $message);
        if (! class_exists($class)) {
            throw new ClassException($message);
        }
    }

    /**
     * @param object $object
     * @param string $method
     * @param string $message
     */
    public function assertMethod($object, $method, $message)
    {
        $this->assertString($method, $message);
        $this->assertObject($object, $message);
        $class = get_class($object);
        // check same method for same class once is enough
        isset($this->checked['func'][$class]) or $this->checked['func'][$class] = [];
        if (! isset($this->checked['func'][$class][$method])) {
            try {
                $reflection = new ReflectionMethod($class, $method);
            } catch (ReflectionException $exception) {
                throw new MethodException($message);
            }
            if ($reflection->isStatic()) {
                throw new MethodException($message);
            }
            $this->checked['func'][$class][$method] = true;
        }
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $message
     */
    public function assertStaticMethod($class, $method, $message)
    {
        $this->assertString($method, $message);
        $this->assertClass($class, $message);
        // check same method for same class once is enough
        isset($this->checked['staticfunc'][$class]) or $this->checked['staticfunc'][$class] = [];
        if (! isset($this->checked['staticfunc'][$class][$method])) {
            try {
                $reflection = new ReflectionMethod($class, $method);
            } catch (ReflectionException $exception) {
                throw new MethodException($message);
            }
            if (! $reflection->isStatic()) {
                throw new MethodException($message);
            }
            $this->checked['staticfunc'][$class][$method] = true;
        }
    }

    /**
     * @param object $object
     * @param string $name
     * @param string $message
     */
    public function assertProperty($object, $name, $message)
    {
        $this->assertString($name, $message);
        $this->assertObject($object, $message);
        $class = get_class($object);
        // check same property for same class once is enough
        isset($this->checked['var'][$class]) or $this->checked['var'][$class] = [];
        if (! isset($this->checked[$class]['var'][$name])) {
            try {
                $reflection = new ReflectionProperty($class, $name);
            } catch (ReflectionException $exception) {
                throw new PropertyException($message);
            }
            if ($reflection->isStatic()) {
                throw new PropertyException($message);
            }
            $this->checked[$class]['var'][$name] = true;
        }
    }

    /**
     * @param string $class
     * @param string $name
     * @param string $message
     */
    public function assertStaticProperty($class, $name, $message)
    {
        $this->assertString($name, $message);
        $this->assertClass($class, $message);
        // check same property for same class once is enough
        isset($this->checked['staticvar'][$class]) or $this->checked['staticvar'][$class] = [];
        if (! isset($this->checked[$class]['staticvar'][$name])) {
            try {
                $reflection = new ReflectionProperty($class, $name);
            } catch (ReflectionException $exception) {
                throw new PropertyException($message);
            }
            if (! $reflection->isStatic()) {
                throw new PropertyException($message);
            }
            $this->checked[$class]['staticvar'][$name] = true;
        }
    }
}
