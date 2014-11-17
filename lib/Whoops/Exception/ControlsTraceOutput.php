<?php

/*
 * This file is part of the Indigo Console package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Whoops\Exception;

/**
 * Implement this interface to explicitly control stack trace
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface ControlsTraceOutput
{
    /**
     * Controls whether trace output should be added
     *
     * @return boolean Returns true if trace can be added, false if not
     */
    public function canAddTrace();
}
