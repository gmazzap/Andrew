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
use Andrew\Exception\ClassException;
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
    const TYPE_CHECKS_MAP = [
        self::GETTER => [
            'assertStaticProperty',
            'Undeclared static properties can not be retrieved.'
        ],
        self::SETTER => [
            'assertStaticProperty',
            'Undeclared static properties can not be set.'
        ],
        self::ISSER  => [
            'assertStaticProperty',
            'Undeclared static properties can not be checked.'
        ],
        self::CALLER => [
            'assertStaticMethod',
            'Undeclared static methods can not be called.'
        ],
    ];

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
     * @var \Closure[]
     */
    private $callbacks = [];

    /**
     * @param string                  $class
     * @param \Andrew\Checker\Checker $checker
     * @throws \Andrew\Exception\ClassException
     * @throws \Andrew\Exception\ArgumentException
     */
    public function __construct($class, Checker $checker = null)
    {
        $checker or $checker = new Checker();
        $checker->assertClass($class, __CLASS__.' expects a fully qualified class name.');
        if (! (new \ReflectionClass($class))->isUserDefined()) {
            throw new ClassException('It is not possible to use static proxy with PHP native classes.');
        }
        $this->checker = $checker;
        $this->class = $class;
        $this->object = (new ReflectionClass($class))->newInstanceWithoutConstructor();
    }

    /**
     * @return \Closure
     */
    public function getter()
    {
        return $this->createCallback(self::GETTER);
    }

    /**
     * @return \Closure
     */
    public function setter()
    {
        return $this->createCallback(self::SETTER);
    }

    /**
     * @return \Closure
     */
    public function isser()
    {
        return $this->createCallback(self::ISSER);
    }

    /**
     * @return void
     * @throws \Andrew\Exception\RuntimeException
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
        return $this->createCallback(self::CALLER);
    }

    /**
     * @param string $which
     * @return \Closure
     */
    private function createCallback($which)
    {
        if (isset($this->callbacks[$which])) {
            // @codeCoverageIgnoreStart
            return $this->callbacks[$which];
            // @codeCoverageIgnoreEnd
        }

        $checker = $this->checker;
        $class = $this->class;

        $caller = function ($key, $value = null) use ($which, $checker, $class) {
            list($checkMethod, $checkMessage) = StaticCallbacks::TYPE_CHECKS_MAP[$which];
            /** @var callable $checkerCallback */
            $checkerCallback = [$checker, $checkMethod];
            $checkerCallback($class, $key, $checkMessage);

            switch ($which) {
                case StaticCallbacks::CALLER:
                    /** @var callable $method */
                    $method = [$class, $key];

                    return $value ? $method(...$value) : $method();
                case StaticCallbacks::GETTER:
                    return static::${$key};
                case StaticCallbacks::SETTER:
                    static::${$key} = $value;
                    break;
                case StaticCallbacks::ISSER:
                    return isset(static::${$key});
            }
        };

        $this->callbacks[$which] = Closure::bind($caller, $this->object, $this->class);

        return $this->callbacks[$which];
    }
}
