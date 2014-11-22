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
use Ulrichsg\Getopt\Getopt;

/**
 * Help commands of application
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Help extends Basic
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'help';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Displays help for a command';

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
    public function execute(Getopt $input, CLImate $output)
    {
        $output->write('Help for '.$input->getOperand(1));
    }
}
