<?php

namespace TimeTracking\Output;

use TimeTracking\Dispatcher;

interface OutputInterface
{
    /**
     * Formats the given $output of the given $controller and the given $action
     *
     * @param Dispatcher $dispatcher
     * @param mixed      $output
     *
     * @return mixed
     */
    public function formatOutput(Dispatcher $dispatcher, $output);

    /**
     * Formats the given $error occurred while executing $controller with $action
     *
     * @param Dispatcher $dispatcher
     * @param \Exception $error
     *
     * @return mixed
     */
    public function formatError(Dispatcher $dispatcher, \Exception $error);
}
