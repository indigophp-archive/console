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
use League\BooBoo\Runner;

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
     * @var Runner
     */
    protected $booboo;

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
     * @param CLImate $climate
     *
     * @return integer
     */
    public function run(CLImate $climate = null)
    {
        if (is_null($climate)) {
            $climate = new CLImate;
        }

        $climate->arguments->add($this->getDefaultArguments());
        $climate->arguments->description($this->getLongVersion());

        $climate->arguments->parse();

        $exitCode = $this->execute($climate);

        return $this->doExit($exitCode);
    }

    /**
     * Execution logic
     *
     * @param CLImate $climate
     *
     * @return mixed
     */
    protected function execute(CLImate $climate)
    {
        if ($climate->arguments->defined('version')) {
            $climate->write($this->getLongVersion());

            return 0;
        }

        if ($climate->arguments->defined('help')) {
            $name = 'help';
        } else {
            $name = $climate->arguments->get('command');
        }

        if (empty($name)) {
            $name = $this->defaultCommand;
        }

        $command = $this->getCommand($name);

        // Argument/option validation should be here?

        return $command->execute($climate);
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
     * Returns the default argument definitions
     *
     * @return array
     */
    private function getDefaultArguments()
    {
        return [
            'help' => [
                'prefix'      => 'h',
                'longPrefix'  => 'help',
                'description' => 'Display this help message',
                'noValue'     => true,
                'castTo'      => 'bool',
            ],
            'version' => [
                'prefix'      => 'V',
                'longPrefix'  => 'version',
                'description' => 'Display this application version',
                'noValue'     => true,
                'castTo'      => 'bool',
            ],
            'command' => [
                'description' => 'Command name',
            ],
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
