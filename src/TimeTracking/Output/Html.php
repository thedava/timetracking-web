<?php

namespace TimeTracking\Output;


use TimeTracking\Dispatcher;
use TimeTracking\Renderer\Php;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToUnderscore;

class Html implements OutputInterface
{
    /** @var Php */
    protected $renderer;

    public function __construct()
    {
        $this->renderer = new Php();
    }

    /**
     * @return Php
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param Php $renderer
     *
     * @return $this
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @return FilterChain
     */
    protected function getFilterChain()
    {
        $filterChain = new FilterChain();
        $filterChain->attach(new CamelCaseToUnderscore());
        $filterChain->attach(new StringToLower());

        return $filterChain;
    }

    /**
     * Formats the given $output of the given $controller and the given $action
     *
     * @param Dispatcher $dispatcher
     * @param mixed      $output
     *
     * @return mixed
     * @throws \Exception
     */
    public function formatOutput(Dispatcher $dispatcher, $output)
    {
        if (!is_array($output)) {
            throw new \Exception('Invalid output given!');
        }
        $this->getRenderer()->setDispatcher($dispatcher);

        $filterChain = $this->getFilterChain();
        return $this->getRenderer()->render('layout', [
            'content' => $this->getRenderer()->render(implode('/', [
                $filterChain->filter($dispatcher->getController(true)),
                $filterChain->filter($dispatcher->getAction(true)),
            ]), $output),
        ]);
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
        $this->getRenderer()->setDispatcher($dispatcher);
        return $this->getRenderer()->render('error/index', [
            'error'       => $error,
            'last_errors' => $dispatcher->getLastErrors(),
        ]);
    }
}
