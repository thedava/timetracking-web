<?php

namespace TimeTrackingTest\Mock\Controller;

use TimeTracking\Controller\AbstractController;

class PHPUnitController extends AbstractController
{
    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return 'PHPUnit';
    }

    public function testAction()
    {
        return 'PHPUnit_Test';
    }

    public function foobarAction()
    {
        return 'PHPUnit_FooBar';
    }
}
