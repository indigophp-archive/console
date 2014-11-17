<?php

/*
 * This file is part of the Indigo Console package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Console\Stub\Command\Sub;

use Indigo\Console\Command\Basic;

/**
 * Stub for testing auto-detect name feature
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Name extends Basic
{
    use \Indigo\Console\Command\AutoDetectName;

    const DESCRIPTION = 'Stub for testing auto-detect name feature';

    public function execute() { }
}
