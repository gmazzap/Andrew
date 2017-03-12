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

    const PROPERTY        = 'property';
    const STATIC_PROPERTY = 'static_property';
    const METHOD          = 'method';
    const STATIC_METHOD   = 'static_method';

    /**
     * @var array
     */
    private static $checked = [
        self::PROPERTY        => [],
        self::STATIC_PROPERTY => [],
        self::METHOD          => [],
        self::STATIC_METHOD   => [],
    ];

    /**
     * @param mixed  $object
     * @param string $message
     * @throws \Andrew\Exception\ArgumentException
     */
    public function assertObject($object, $message)
    {
        if ( ! is_object($object)) {
            throw new ArgumentException($message);
        }
    }

    /**
     * @param string $string
     * @param string $message
     * @throws \Andrew\Exception\ArgumentException
     */
    public function assertString($string, $message)
    {
        if ( ! is_string($string)) {
            throw new ArgumentException($message);
        }
    }

    /**
     * @param string $class
     * @param string $message
     * @throws \Andrew\Exception\ArgumentException
     * @throws \Andrew\Exception\ClassException
     */
    public function assertClass($class, $message)
    {
        $this->assertString($class, $message);
        if ( ! class_exists($class)) {
            throw new ClassException($message);
        }
    }

    /**
     * @param mixed  $object
     * @param string $method
     * @param string $message
     * @throws \Andrew\Exception\ArgumentException
     * @throws \Andrew\Exception\MethodException
     */
    public function assertMethod($object, $method, $message)
    {
        $this->assertString($method, $message);
        $this->assertObject($object, $message);
        $class = get_class($object);

        // To check a method on same class is safely done once per request
        if (isset(self::$checked[self::METHOD][$class][$method])) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        try {
            $reflection = new ReflectionMethod($class, $method);
        } catch (ReflectionException $exception) {
            throw new MethodException($message." ({$exception->getMessage()})");
        }
        if ($reflection->isStatic()) {
            throw new MethodException($message);
        }

        if ( ! isset(self::$checked[self::METHOD][$class])) {
            self::$checked[self::METHOD][$class] = [];
        }

        self::$checked[self::METHOD][$class][$method] = true;
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $message
     * @throws \Andrew\Exception\ArgumentException
     * @throws \Andrew\Exception\ClassException
     * @throws \Andrew\Exception\MethodException
     */
    public function assertStaticMethod($class, $method, $message)
    {
        $this->assertString($method, $message);
        $this->assertClass($class, $message);

        // To check a static method on same class is safely done once per request
        if (isset(self::$checked[self::STATIC_METHOD][$class][$method])) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        try {
            $reflection = new ReflectionMethod($class, $method);
        } catch (ReflectionException $exception) {
            throw new MethodException($message);
        }
        if ( ! $reflection->isStatic()) {
            throw new MethodException($message);
        }

        if ( ! isset(self::$checked[self::STATIC_METHOD][$class])) {
            self::$checked[self::STATIC_METHOD][$class] = [];
        }

        self::$checked[self::STATIC_METHOD][$class][$method] = true;
    }

    /**
     * @param mixed  $object
     * @param string $name
     * @param string $message
     * @throws \Andrew\Exception\ArgumentException
     * @throws \Andrew\Exception\PropertyException
     */
    public function assertProperty($object, $name, $message)
    {
        $this->assertString($name, $message);
        $this->assertObject($object, $message);
        $class = get_class($object);

        // To check a property on same class is safely done once per request
        if (isset(self::$checked[self::PROPERTY][$class][$name])) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        try {
            $reflection = new ReflectionProperty($class, $name);
        } catch (ReflectionException $exception) {
            throw new PropertyException($message);
        }
        if ($reflection->isStatic()) {
            throw new PropertyException($message);
        }

        if ( ! isset(self::$checked[self::PROPERTY][$class])) {
            self::$checked[self::PROPERTY][$class] = [];
        }

        self::$checked[self::PROPERTY][$class][$name] = true;
    }

    /**
     * @param string $class
     * @param string $name
     * @param string $message
     * @throws \Andrew\Exception\ArgumentException
     * @throws \Andrew\Exception\ClassException
     * @throws \Andrew\Exception\PropertyException
     */
    public function assertStaticProperty($class, $name, $message)
    {
        $this->assertString($name, $message);
        $this->assertClass($class, $message);

        // To check a static property on same class is safely done once per request
        if (isset(self::$checked[self::STATIC_PROPERTY][$class][$name])) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        try {
            $reflection = new ReflectionProperty($class, $name);
        } catch (ReflectionException $exception) {
            throw new PropertyException($message);
        }
        if ( ! $reflection->isStatic()) {
            throw new PropertyException($message);
        }

        if ( ! isset(self::$checked[self::STATIC_PROPERTY][$class])) {
            self::$checked[self::STATIC_PROPERTY][$class] = [];
        }

        self::$checked[self::STATIC_PROPERTY][$class][$name] = true;
    }
}
