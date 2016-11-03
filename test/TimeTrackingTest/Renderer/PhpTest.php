<?php
/**
 * Created by PhpStorm.
 * User: dava
 * Date: 02.11.16
 * Time: 00:08
 */

namespace TimeTrackingTest\Renderer;


use TimeTracking\Dispatcher;
use TimeTracking\Renderer\Php;
use TimeTracking\View\Helper\TitleViewHelper;

class PhpTest extends \PHPUnit_Framework_TestCase
{
    /** @var Php */
    protected $renderer;

    protected function setUp()
    {
        $this->renderer = new Php();
        $this->renderer->setDispatcher(new Dispatcher([]));
    }

    public function testInvoke()
    {
        $this->assertEquals('DefaultTitle', $this->renderer->invoke(TitleViewHelper::class));
    }

    public function testInvokeInvalid()
    {
        $this->setExpectedExceptionRegExp(\Exception::class, '/^\"stdClass\" is not a valid view helper/');
        $this->renderer->invoke(\stdClass::class);
    }

    public function specialCharsDataProvider()
    {
        return [
            ['&ouml;', 'ö'],
            ['&uuml;', 'ü'],
            ['&auml;', 'ä'],

            ['&lt;', '<'],
            ['&gt;', '>'],

            ['&quot;', '"'],
        ];
    }

    /**
     * @dataProvider specialCharsDataProvider
     *
     * @param $expected
     * @param $char
     */
    public function testEscape($expected, $char)
    {
        $this->assertEquals($expected, $this->renderer->escape($char));
    }

    public function testTemplatePath()
    {
        $refClass = new \ReflectionClass(Php::class);
        $refMethod = $refClass->getMethod('getTemplatePath');
        $refMethod->setAccessible(true);

        $layout = $refMethod->invoke($this->renderer, 'layout');
        $this->assertTrue(file_exists($layout));
        $this->assertStringEndsWith('view/layout.phtml', $layout);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Php
     */
    protected function getRenderer()
    {
        $renderer = $this->getMockBuilder(Php::class)
            ->setMethods(['getTemplatePath'])
            ->getMock();

        $renderer->method('getTemplatePath')->willReturnCallback(function ($template) {
            return _ROOT_ . '/test/view/' . $template . '.phtml';
        });

        return $renderer;
    }

    public function testRender()
    {
        $this->assertEquals('PHPUNIT:' . __METHOD__, $this->getRenderer()->render('phpunit', ['suffix' => __METHOD__]));
    }

    public function testRenderFail()
    {
        $this->setExpectedExceptionRegExp(\Exception::class, '/^Unable to render template/');
        $this->getRenderer()->render('nothing');
    }
}
