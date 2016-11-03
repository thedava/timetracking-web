<?php

namespace TimeTrackingTest\Mock\Controller;

use TimeTracking\Controller\AbstractController;

class PHPUnitDefaultController extends AbstractController
{
    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return 'PHPUnit_Default';
    }
}
