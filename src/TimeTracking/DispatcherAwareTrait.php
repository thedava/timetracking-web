<?php
/**
 * Created by PhpStorm.
 * User: dava
 * Date: 01.11.16
 * Time: 23:37
 */

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
