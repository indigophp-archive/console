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

use Whoops\Exception\Frame;

/**
 * Handler for Trace output
 *
 * Based on the original PlainTextHandler
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class TraceHandler extends Handler
{
    const VAR_DUMP_PREFIX = '   | ';

    /**
     * Add trace to the output
     *
     * @var boolean
     */
    protected $shouldAddTrace = true;

    /**
     * Add function arguments to the trace
     *
     * @var boolean|integer
     */
    protected $shouldAddFunctionArgs = false;

    /**
     * Function argument limit
     *
     * @var integer
     */
    protected $traceFunctionArgsLimit = 1024;

    /**
     * Checks whether an exception having trace control should be ignored
     *
     * @var boolean
     */
    protected $ignoreTraceControl = false;

    /**
     * Add error trace to output
     *
     * @param boolean $shouldAddTrace
     */
    public function shouldAddTrace($shouldAddTrace = true)
    {
        $this->shouldAddTrace = (bool) $shouldAddTrace;
    }

    /**
     * Add function args to trace
     *
     * Set to True for all frame args, or integer for the n first frame args
     *
     * @param  boolean $shouldAddFunctionArgs
     */
    public function shouldAddFunctionArgs($shouldAddFunctionArgs = true)
    {
        if (!is_integer($shouldAddFunctionArgs)) {
            $shouldAddFunctionArgs = (bool) $shouldAddFunctionArgs;
        }

        $this->shouldAddFunctionArgs = $shouldAddFunctionArgs;
    }

    /**
     * Set the size limit in bytes of frame arguments var_dump output.
     * If the limit is reached, the var_dump output is discarded.
     * Prevent memory limit errors.
     *
     * @param integer
     */
    public function setTraceFunctionArgsLimit($traceFunctionArgsLimit)
    {
        $this->traceFunctionArgsLimit = (int) $traceFunctionArgsLimit;
    }

    /**
     * Sets the ignore trace control check
     *
     * @param boolean $ignoreTraceControl
     */
    public function ignoreTraceControl($ignoreTraceControl = true)
    {
        $this->ignoreTraceControl = (bool) $ignoreTraceControl;
    }

    /**
     * Returns the exception trace as plain text
     *
     * @return string
     */
    protected function getTrace()
    {
        $response = '';

        if ($this->shouldAddTrace) {
            $inspector = $this->getInspector();
            $frames = $inspector->getFrames();
            $response = "\nStack trace:";
            $line = 1;

            foreach ($frames as $frame) {
                /** @var Frame $frame */
                $class = $frame->getClass();
                $template = $class ? "\n%3d. %s->%s() %s:%d%s" : "\n%3d. %s%s() %s:%d%s";
                $frameArgs = '';

                if ($this->shouldAddFunctionArgs === true or $this->shouldAddFunctionArgs < $line) {
                    $frameArgs = $this->getFrameArgs($frame);
                }

                $response .= sprintf(
                    $template,
                    $line,
                    $class,
                    $frame->getFunction(),
                    $frame->getFile(),
                    $frame->getLine(),
                    $frameArgs
                );

                $line++;
            }
        }

        return $response;
    }

    /**
     * Returns the frame args var_dump
     *
     * @param \Whoops\Exception\Frame $frame
     *
     * @return string
     */
    private function getFrameArgs(Frame $frame)
    {
        // Dump the arguments:
        ob_start();
        var_dump($frame->getArgs());
        if (ob_get_length() > $this->traceFunctionArgsLimit) {
            // The argument var_dump is to big.
            // Discarded to limit memory usage.
            ob_clean();
            return sprintf(
                "\n%sArguments dump length greater than %d Bytes. Discarded.",
                self::VAR_DUMP_PREFIX,
                $this->traceFunctionArgsLimit
            );
        }

        return sprintf("\n%s",
            preg_replace('/^/m', self::VAR_DUMP_PREFIX, ob_get_clean())
        );
    }
}
