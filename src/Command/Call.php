<?php

/*
 * This file is part of the Indigo Console package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Console\Command;

use Indigo\Console\Command;
use League\CLImate\CLImate;

/**
 * Helps creating a command from a callable
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Call implements Command
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param callable $callable
     */
    public function __construct(
        callable $callable,
        $name = 'Callable command',
        $description = 'This command accepts a callable and invokes it as execution'
    ) {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return call_user_func_array($this->callable, func_get_args());
    }
}
