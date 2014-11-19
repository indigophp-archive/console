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
use Whoops\Run as Whoops;

/**
 * Console application
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Application
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var Whoops
     */
    protected $exceptionHandler;

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
     * @param string $name
     * @param string $version
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->name = $name;
        $this->version = $version;
        $this->defaultCommand = 'list';

        $this->addCommands($this->getDefaultCommands());
    }

    /**
     * Returns the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function setExceptionHandler(Whoops $handler = null)
    {
        if (!is_null($this->exceptionHandler)) {
            $this->exceptionHandler->unregister();
        }

        if (!is_null($handler)) {
            $handler->register();
        }

        $this->exceptionHandler = $handler;
    }

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
     * Runs the application's proper command
     *
     * @return integer
     */
    public function run(array $args, CLImate $output = null)
    {
        if (is_null($output)) {
            $output = new CLImate;
        }

        $name = array_shift($args);

        if (empty($name)) {
            $name = $this->defaultCommand;
        }

        $command = $this->getCommand($name);

        $exitCode = $command->execute($args, $output);

        return is_numeric($exitCode) ? (int) $exitCode : 0;
    }

    /**
     * Returns commands added to the application by default
     *
     * @return Command[]
     */
    protected function getDefaultCommands()
    {
        return [new Command\Ls($this)];
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
