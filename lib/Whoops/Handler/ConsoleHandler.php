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

use Whoops\Exception\ControlsTraceOutput;

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
     * {@inheritdoc}
     */
    public function handle()
    {
        if (PHP_SAPI !== 'cli') {
            return Handler::DONE;
        }

        $exception = $this->getException();

        if (
            $exception instanceof ControlsTraceOutput and
            $this->ignoreTraceControl === false and
            $exception->canAddTrace() === false
        ) {
            $trace = '';
        } else {
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
