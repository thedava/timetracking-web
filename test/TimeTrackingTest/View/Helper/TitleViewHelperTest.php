<?php

namespace TimeTrackingTest\View\Helper;


use TimeTracking\Dispatcher;
use TimeTracking\Renderer\Php;
use TimeTracking\View\Helper\TitleViewHelper;
use TimeTrackingTest\DispatcherTest;

class TitleViewHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var TitleViewHelper */
    protected $viewHelper;

    protected function setUp()
    {
        $renderer = new Php();

        $this->viewHelper = new TitleViewHelper($renderer);
    }

    protected function getDispatcher(array $params = [])
    {
        $dispatcher = new Dispatcher($params, DispatcherTest::getDispatcherOptions());
        $dispatcher->dispatch();

        return $dispatcher;
    }

    public function testRenderDefault()
    {
        $this->viewHelper->getRenderer()->setDispatcher($this->getDispatcher());

        $this->assertEquals('DefaultTitle', $this->viewHelper->render());
    }

    public function specificParamsDataProvider()
    {
        return [
            ['PHPUnit', 'index', 'PHPUnitTitle'],
            ['PHPUnit', 'test', 'PHPUnitTestTitle'],
            ['PHPUnit', 'foobar', 'PHPUnitDefaultTitle'],
            ['PHPUnitError', 'index', 'PHPUnitErrorIndexTitle'],
        ];
    }

    /**
     * @dataProvider specificParamsDataProvider
     *
     * @param $controller
     * @param $action
     * @param $expectedTitle
     */
    public function testRenderSpecific($controller, $action, $expectedTitle)
    {
        $dispatcher = $this->getDispatcher([
            DispatcherTest::PARAM_CONTROLLER => $controller,
            DispatcherTest::PARAM_ACTION     => $action,
        ]);
        $this->viewHelper->getRenderer()->setDispatcher($dispatcher);

        $this->assertEquals($expectedTitle, $this->viewHelper->render(), 'The title of ' . $dispatcher->getController() . '::' . $dispatcher->getAction() . ' is invalid!');
    }

    public function testToString()
    {
        $this->viewHelper->getRenderer()->setDispatcher($this->getDispatcher());

        $this->assertEquals('DefaultTitle', strval($this->viewHelper));
    }
}
