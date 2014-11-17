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
    use Router;

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
}
