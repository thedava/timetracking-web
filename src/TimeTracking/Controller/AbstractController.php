<?php

namespace TimeTracking\Controller;

use TimeTracking\Dispatcher;
use TimeTracking\DispatcherAwareTrait;

abstract class AbstractController
{
    use DispatcherAwareTrait;

    /**
     * AbstractController constructor.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Default action of every controller
     *
     * @return mixed
     */
    abstract public function indexAction();
}
