<?php
/*
 * This file is part of the Andrew package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Andrew\Tests;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
class Stub
{

    /**
     * @var string
     */
    public static $public_static_var = 'Public Static Var';

    /**
     * @var string
     */
    private static $private_static_var = 'Private Static Var';

    /**
     * @var string
     */
    private $private_var = 'Private Var';

    /**
     * @var string
     */
    public $public_var = 'Public Var';

    /**
     * @return string
     */
    public static function publicStaticMethod()
    {
        return 'Public Static Method';
    }

    /**
     * @param  string $var
     * @return string
     */
    private static function privateStaticMethod($var = '')
    {
        return 'Private Static Method'.$var;
    }

    /**
     * @return string
     */
    public static function staticGet()
    {
        return self::$private_static_var;
    }

    /**
     * @return string
     */
    public static function staticCheck()
    {
        /** @noinspection UnSafeIsSetOverArrayInspection */
        return isset(self::$private_static_var);
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return func_get_args();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'I am the Stub';
    }

    /**
     * @return string
     */
    public function publicDynamicMethod()
    {
        return 'Public Dynamic Method';
    }

    /**
     * @param  string $var
     * @return string
     */
    private function privateDynamicMethod($var = '')
    {
        return 'Private Dynamic Method'.$var;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->private_var;
    }

    /**
     * @return string
     */
    public function check()
    {
        /** @noinspection UnSafeIsSetOverArrayInspection */
        return isset($this->private_var);
    }
}
