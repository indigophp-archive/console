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

use Indigo\Console\Command\Collection;
use Indigo\Console\Exception\CommandNotFound;

/**
 * Command Router for Application and Collection Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait Router
{
    /**
     * @var Command[]
     */
    protected $commands = [];

    /**
     * Default command name
     *
     * @var string
     */
    protected $defaultCommand;

    /**
     * Returns a command by its name
     *
     * @param string $command
     *
     * @return Command
     */
    public function getCommand($command)
    {
        $this->ensureCommandExists($command);

        return $this->commands[$command];
    }

    /**
     * Returns all commands
     *
     * @return Command[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Checks whether the command exists
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasCommand($name)
    {
        return isset($this->commands[$name]);
    }

    /**
     * Adds a command to the application
     *
     * @param Command $command
     */
    public function addCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * Adds an array of Commands to the application
     *
     * @param Command[] $commands
     */
    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }
    }

    /**
     * Sets the default command
     *
     * @param string $command
     */
    public function setDefaultCommand($command)
    {
        if ($command instanceof Command) {
            if (!$this->hasCommand($name)) {
                $this->addCommand($command);
            }

            $command = $command->getName();
        }

        $this->ensureCommandExists($name);
    }

    /**
     * Ensures that a command exists
     *
     * @param string $command
     *
     * @throws CommandNotFound If $command is not found in the application
     */
    private function ensureCommandExists($command)
    {
        if (!$this->hasCommand($command)) {
            throw new CommandNotFound($command, $this->commands);
        }
    }
}
