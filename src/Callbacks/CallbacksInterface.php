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

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Andrew
 */
interface CallbacksInterface
{

    const GETTER   = 'getter';
    const SETTER   = 'setter';
    const ISSER    = 'isser';
    const UNSETTER = 'unsetter';
    const CALLER   = 'caller';

    /**
     * @return callable
     */
    public function getter();

    /**
     * @return callable
     */
    public function setter();

    /**
     * @return callable
     */
    public function isser();

    /**
     * @return callable
     */
    public function unsetter();

    /**
     * @return callable
     */
    public function caller();
}
