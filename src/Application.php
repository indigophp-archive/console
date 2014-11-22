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

use Ulrichsg\Getopt\Getopt;
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
     * The application should exit when the command is executed
     *
     * @var boolean
     */
    protected $autoExit = true;

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

    /**
     * Sets or removes the exception handler
     *
     * @param Whoops $handler
     */
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
     * @param string $name
     *
     * @return Command
     */
    public function getCommand($name)
    {
        $this->ensureCommandExists($name);

        return $this->commands[$name];
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
     * @param string $name
     */
    public function setDefaultCommand($name)
    {
        $this->ensureCommandExists($name);

        $this->defaultCommand = $name;
    }

    /**
     * The application should exit when the command is executed
     *
     * @param boolean $autoExit
     */
    public function setAutoExit($autoExit = true)
    {
        $this->autoExit = (bool) $autoExit;
    }

    /**
     * Runs the application
     *
     * @param Getopt  $getopt
     * @param CLImate $output
     *
     * @return integer
     */
    public function run(Getopt $getopt = null, CLImate $output = null)
    {
        if (is_null($output)) {
            $output = new CLImate;
        }

        // if version option is passed return it

        // The first argument is always the command name
        // except help option is passed
        $name = array_shift($args);

        if (empty($name)) {
            $name = $this->defaultCommand;
        }

        $command = $this->getCommand($name);

        // Argument/option validation should be here?

        $exitCode = $command->execute($args, $output);

        return $this->doExit($exitCode);
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

    /**
     * Exits the application
     *
     * @param mixed $code
     *
     * @return integer
     */
    private function doExit($code)
    {
        $code = $this->normalizeExitCode($code, $this->autoExit);

        if ($this->autoExit) {
            exit($code);
        }

        return $code;
    }

    /**
     * Normalizes exit code
     *
     * @param mixed   $code
     * @param boolean $validate
     *
     * @return integer
     */
    private function normalizeExitCode($code, $validate = false)
    {
        $code = is_numeric($code) ? (int) $code : 0;

        // Maximum value of real exit code is 255
        if ($validate and $code > 255) {
            $code = 255;
        }

        return $code;
    }
}
