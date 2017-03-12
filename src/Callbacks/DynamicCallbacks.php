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
use Closure;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
final class DynamicCallbacks implements CallbacksInterface
{
    const TYPE_CHECKS_MAP = [
        self::GETTER   => ['assertProperty', 'Undeclared properties can not be retrieved.'],
        self::SETTER   => ['assertProperty', 'Undeclared properties can not be set.'],
        self::ISSER    => ['assertProperty', 'Undeclared properties can not be checked.'],
        self::UNSETTER => ['assertProperty', 'Undeclared properties can not be unset.'],
        self::CALLER   => ['assertMethod', 'Undeclared methods can not be called.'],
    ];

    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed
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
     * @var bool
     */
    private $userDefined;

    /**
     * @param mixed                   $object
     * @param \Andrew\Checker\Checker $checker
     * @throws \Andrew\Exception\ClassException
     * @throws \Andrew\Exception\ArgumentException
     */
    public function __construct($object, Checker $checker = null)
    {
        $checker or $checker = new Checker();
        $checker->assertObject($object, __CLASS__.' expects an object.');
        $class = get_class($object);
        if (ltrim($class, '\\') === 'stdClass') {
            throw new ClassException('It is not possible to use proxy with stdClass.');
        }
        $this->checker = $checker;
        $this->object = $object;
        $this->class = $class;
        $this->userDefined = (new \ReflectionClass($class))->isUserDefined();
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
     * @return \Closure
     */
    public function unsetter()
    {
        return $this->createCallback(self::UNSETTER);
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
        $object = $this->object;

        $caller = function ($key, $value = null) use ($which, $checker, $object) {
            list($checkMethod, $checkMessage) = DynamicCallbacks::TYPE_CHECKS_MAP[$which];
            /** @var callable $checkerCallback */
            $checkerCallback = [$checker, $checkMethod];
            $checkerCallback($object, $key, $checkMessage);

            switch ($which) {
                case DynamicCallbacks::CALLER:
                    /** @var callable $method */
                    $method = [$this, $key];

                    return $value ? $method(...$value) : $method();
                case DynamicCallbacks::GETTER:
                    return $this->{$key};
                case DynamicCallbacks::SETTER:
                    $this->{$key} = $value;
                    break;
                case DynamicCallbacks::ISSER:
                    return isset($this->{$key});
                case DynamicCallbacks::UNSETTER:
                    unset($this->{$key});
                    break;
            }
        };

        $this->callbacks[$which] = $this->userDefined
            ? Closure::bind($caller, $this->object, $this->class)
            : Closure::bind($caller, $this->object);

        return $this->callbacks[$which];
    }
}
