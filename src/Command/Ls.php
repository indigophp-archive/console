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

use Indigo\Console\Application;
use League\CLImate\CLImate;

/**
 * Lists commands of application
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Ls extends Basic
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'list';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Lists commands registered in application';

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $args, CLImate $output)
    {
        $commands = $this->application->getCommands();
        $table = [];

        foreach ($commands as $name => $command) {
            $table[] = [
                sprintf('<green>%s</green>', $name),
                $command->getDescription(),
            ];
        }

        $output->yellow('Available commands:');
        $output->table($table);
    }
}
