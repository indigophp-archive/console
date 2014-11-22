<?php

/*
 * This file is part of the Indigo Console package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Whoops\Handler;

use League\CLImate\CLImate;

/**
 * Handler for Console
 *
 * Based on the original PlainTextHandler
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ConsoleHandler extends TraceHandler
{
    /**
     * @var CLImate
     */
    protected $climate;

    /**
     * @param CLImate $climate
     */
    public function __construct(CLImate $climate = null)
    {
        $this->climate = $climate ?: new CLImate;
    }

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        if (PHP_SAPI !== 'cli') {
            return Handler::DONE;
        }

        $exception = $this->getInspector()->getException();
        $trace = '';

        if ($this->canAddTrace()) {
            $trace = $this->getTrace();
        }

        $response = sprintf(
            "%s: %s in file %s on line %d%s\n",
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $trace
        );

        echo $response;

        return Handler::QUIT;
    }
}
