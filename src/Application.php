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
use Ulrichsg\Getopt\Option;
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
    public function __construct($name = null, $version = null)
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
     * Returns a string representation of the application info
     *
     * @return string
     */
    public function getLongVersion()
    {
        $name = $this->getName();
        $version = $this->getVersion();

        if (!is_null($name) and !is_null($name)) {
            return sprintf('<info>%s</info> version <comment>%s</comment>', $name, $version);
        }

        return '<info>Console application</info>';
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
     * @param Getopt  $input
     * @param CLImate $output
     *
     * @return integer
     */
    public function run(Getopt $input = null, CLImate $output = null)
    {
        if (is_null($input)) {
            $input = new Getopt;
        }

        if (is_null($output)) {
            $output = new CLImate;
        }

        $input->addOptions($this->getDefaultOptions());

        $input->parse();

        $exitCode = $this->execute($input, $output);

        return $this->doExit($exitCode);
    }

    /**
     * Execution logic
     *
     * @param Getopt  $input
     * @param CLImate $output
     *
     * @return mixed
     */
    protected function execute(Getopt $input, CLImate $output)
    {
        if ($input['version']) {
            $output->write($this->getLongVersion());

            return 0;
        }

        if ($input['help']) {
            $name = 'help';
        } else {
            $name = $input->getOperand(0);
        }

        if (empty($name)) {
            $name = $this->defaultCommand;
        }

        $command = $this->getCommand($name);

        // Argument/option validation should be here?

        return $command->execute($input, $output);
    }

    /**
     * Returns commands added to the application by default
     *
     * @return Command[]
     */
    protected function getDefaultCommands()
    {
        return [new Command\Help($this), new Command\Ls($this)];
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
     * Returns the default option definitions
     *
     * @return Option[]
     */
    private function getDefaultOptions()
    {
        return [
            new Option('h', 'help', Getopt::NO_ARGUMENT, 'Display this help message.'),
            new Option('V', 'version', Getopt::NO_ARGUMENT, 'Display this application version.'),
        ];
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
