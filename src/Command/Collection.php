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

/**
 * Collection of commands making it possible to implement subcommands
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Collection extends Basic
{
    use \Indigo\Console\Router;

    /**
     * @param string $name
     * @param string $description
     */
    public function __construct($name, $description = 'Collection of commands')
    {
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $args, CLImate $output)
    {
        $name = array_shift($args);

        $command = $this->getCommand($name);

        return $command->execute($args, $output);
    }
}
