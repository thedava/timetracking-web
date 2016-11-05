<?php

namespace TimeTrackingTest;


use TimeTracking\Dispatcher;
use TimeTrackingTest\Mock\Controller\PHPUnitController;
use TimeTrackingTest\Mock\Controller\PHPUnitDefaultController;
use TimeTrackingTest\Mock\Controller\PHPUnitErrorController;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    const PARAM_CONTROLLER = 'controller';
    const PARAM_ACTION = 'action';

    public static function getDispatcherOptions()
    {
        return [
            'controller_param'   => static::PARAM_CONTROLLER,
            'controller_prefix'  => 'TimeTrackingTest\\Mock\\Controller\\',
            'controller_default' => 'PHPUnitDefault',
            'controller_error'   => 'PHPUnitError',
            'action_param'       => static::PARAM_ACTION,
        ];
    }

    /**
     * @param array $params
     *
     * @return Dispatcher
     */
    protected function getDispatcher($params = [])
    {
        return new Dispatcher($params, $this->getDispatcherOptions());
    }

    public function testDispatchSuccessWithDefaultController()
    {
        $dispatcher = $this->getDispatcher();
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Default', $result);
        $this->assertEquals(PHPUnitDefaultController::class, $dispatcher->getController());
        $this->assertEquals('indexAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnitDefault', $dispatcher->getController(true));
        $this->assertEquals('index', $dispatcher->getAction(true));
    }

    public function testDispatchSuccessWithSpecifiedController()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit', $result);
        $this->assertEquals(PHPUnitController::class, $dispatcher->getController());
        $this->assertEquals('indexAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnit', $dispatcher->getController(true));
        $this->assertEquals('index', $dispatcher->getAction(true));
    }

    public function testDispatchSuccessWithSpecifiedControllerAndAction()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
            static::PARAM_ACTION     => 'test',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Test', $result);
        $this->assertEquals(PHPUnitController::class, $dispatcher->getController());
        $this->assertEquals('testAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnit', $dispatcher->getController(true));
        $this->assertEquals('test', $dispatcher->getAction(true));
    }

    public function testDispatchFailWithEmptyValues()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => '',
            static::PARAM_ACTION     => '',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Error', $result);
        $this->assertEquals(PHPUnitErrorController::class, $dispatcher->getController());
        $this->assertEquals('indexAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnitError', $dispatcher->getController(true));
        $this->assertEquals('index', $dispatcher->getAction(true));
    }

    public function testDispatchFailWithControllerNotFound()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnitNotExists',
            static::PARAM_ACTION     => '',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Error', $result);

        $match = false;
        foreach ($dispatcher->getLastErrors() as $error) {
            if (strpos($error, 'There is no controller') === 0) {
                $match = true;
                break;
            }
        }
        $this->assertTrue($match, 'The expected "There is no controller" error message is missing!');
    }

    public function testDispatchFailWithControllerNotChildOfAbstractController()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnitOrphan',
            static::PARAM_ACTION     => '',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Error', $result);

        $match = false;
        foreach ($dispatcher->getLastErrors() as $error) {
            if (strpos($error, 'does not extend') !== false) {
                $match = true;
                break;
            }
        }
        $this->assertTrue($match, 'The expected "does not extend" error message is missing!');
    }

    public function testGetOptions()
    {
        $this->assertArraySubset(static::getDispatcherOptions(), $this->getDispatcher()->getOptions());
    }
}
