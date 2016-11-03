<?php

namespace TimeTrackingTest\Mock\Controller;

use TimeTracking\Controller\AbstractController;

class PHPUnitErrorController extends AbstractController
{
    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return 'PHPUnit_Error';
    }
}
