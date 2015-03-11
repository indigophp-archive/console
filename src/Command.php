<?php

/*
 * This file is part of the Indigo Console package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Console;

use League\CLImate\CLImate;
use Ulrichsg\Getopt\Getopt;

/**
 * Command details
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Command
{
    /**
     * Returns the name of the command
     *
     * Should be in form of the following:
     * - name
     * - namespace:name
     * - namespace:subns:name
     * - etc.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the description of the command
     *
     * @return string
     */
    public function getDescription();

    /**
     * Execution logic
     *
     * A command itself should always be context dependent
     * To achieve this the dependencies are injected runtime instead of construction time
     * This way a command is never coupled to its dependencies
     *
     * As soon as PHP 5.6 is spread enough, variadic operator will be passed
     * Till then use func_get_args() and shift the first argument
     * Optional arguments can also be used as the validation of arguments is done elsewhere
     *
     * @param CLImate $output
     *
     * @return integer Exit code
     */
    public function execute(CLImate $output);
}
