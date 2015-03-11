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
 * List commands of application
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
     * Current application context
     *
     * @var Application
     */
    protected $application;

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
    public function execute(CLImate $output)
    {
        $commands = $this->application->getCommands();
        $table = [];

        foreach ($commands as $name => $command) {
            $table[] = [
                sprintf('<info>%s</info>', $name),
                $command->getDescription(),
            ];
        }

        $output->comment('Available commands:');
        $output->table($table);
    }
}
