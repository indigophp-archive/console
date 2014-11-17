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
use Indigo\Console\Command;

/**
 * Basic command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Basic implements Command
{
    /**
     * Name of the command
     *
     * @var string
     */
    protected $name;

    /**
     * Description of the command
     *
     * @var string
     */
    protected $description;

    /**
     * Current application context
     *
     * @var Application
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * {@inheritdoc}
     */
    public function setApplication(Application $application = null)
    {
        $this->application = $application;
    }
}
