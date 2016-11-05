<?php

namespace TimeTracking;


trait DispatcherAwareTrait
{
    /** @var Dispatcher */
    protected $dispatcher;

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
