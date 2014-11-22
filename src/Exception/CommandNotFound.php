<?php

/*
 * This file is part of the Indigo Console package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Console\Exception;

use Whoops\Exception\Internal;

/**
 * Thrown when a command is not found
 *
 * Optionally looks for similar command names in the application
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class CommandNotFound extends \RuntimeException implements Internal
{
    /**
     * Name of the command
     *
     * @var string
     */
    protected $command;

    /**
     * List of commands on the same level
     *
     * @var Command[]
     */
    protected $commands;

    /**
     * @param string $command
     */
    public function __construct($command, array $commands = [])
    {
        $this->command = $command;
        $this->commands = $commands;

        // If alternatives are added as a second argument, the message should be modified based on that?
    }

    /**
     * {@inheritdoc}
     */
    public function canAddTrace()
    {
        return false;
    }
}
