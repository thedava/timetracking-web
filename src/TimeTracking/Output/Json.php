<?php

namespace TimeTracking\Output;

use TimeTracking\Dispatcher;

class Json implements OutputInterface
{
    /**
     * Returns all Json Options
     *
     * @param array $params
     *
     * @return int
     */
    protected function getJsonOptions(array $params)
    {
        $options = 0;

        if (isset($params['pretty'])) {
            $options = $options | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;
        }

        return $options;
    }

    /**
     * Formats the given $output of the given $controller and the given $action
     *
     * @param Dispatcher $dispatcher
     * @param mixed      $output
     *
     * @return mixed
     */
    public function formatOutput(Dispatcher $dispatcher, $output)
    {
        return json_encode([
            'success' => true,
            'result'  => $output,
        ], $this->getJsonOptions($dispatcher->getParams()));
    }

    /**
     * Formats the given $error occurred while executing $controller with $action
     *
     * @param Dispatcher $dispatcher
     * @param \Exception $error
     *
     * @return mixed
     */
    public function formatError(Dispatcher $dispatcher, \Exception $error)
    {
        return json_encode([
            'success'          => false,
            'controller'       => $dispatcher->getController(),
            'action'           => $dispatcher->getAction(),
            'error_message'    => $error->getMessage(),
            'error_stacktrace' => $error->getTraceAsString(),
            'result'           => null,
        ], $this->getJsonOptions($dispatcher->getParams()));
    }
}
