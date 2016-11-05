<?php

namespace TimeTrackingTest\Output;

use TimeTracking\Dispatcher;
use TimeTracking\Output\Html;
use TimeTracking\Renderer\Php;
use TimeTrackingTest\DispatcherTest;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
    /** @var Html */
    protected $html;

    public function setUp()
    {
        $this->html = new Html();

        $renderer = self::getMockBuilder(Php::class)
            ->setMethods(['getTemplatePath'])
            ->getMock();

        $renderer->method('getTemplatePath')->willReturnCallback(function ($template) {
            return _ROOT_ . '/test/view/' . $template . '.phtml';
        });
        $this->html->setRenderer($renderer);
    }

    protected function getDispatcher(array $params = [])
    {
        $dispatcher = new Dispatcher($params, DispatcherTest::getDispatcherOptions());
        $dispatcher->dispatch();

        return $dispatcher;
    }

    public function testFormatOutput()
    {
        $dispatcher = $this->getDispatcher();

        $result = $this->html->formatOutput($dispatcher, ['suffix' => __METHOD__]);
        $this->assertEquals('index:' . __METHOD__, $result);
    }

    public function testFormatOutputWithInvalidOutput()
    {
        $this->setExpectedExceptionRegExp(\Exception::class, '/Invalid output given/');
        $this->html->formatOutput($this->getDispatcher(), 'invalidOutputBecauseOutputShouldAlwaysBeAnArray');
    }

    public function testFormatError()
    {
        $dispatcher = $this->getDispatcher();

        $result = $this->html->formatError($dispatcher, new \Exception('error:' . __METHOD__));
        $this->assertEquals('error:' . __METHOD__, $result);
    }
}
