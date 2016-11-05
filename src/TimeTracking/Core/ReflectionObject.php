<?php

namespace TimeTracking\Core;

class ReflectionObject extends \ReflectionObject
{
    /** @var object */
    protected $object;

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * ReflectionObject constructor.
     *
     * @param object $argument
     */
    public function __construct($argument)
    {
        parent::__construct($argument);

        $this->object = $argument;
    }
}
