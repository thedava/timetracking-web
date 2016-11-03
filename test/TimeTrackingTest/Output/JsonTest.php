<?php

namespace TimeTrackingTest\Output;


use TimeTracking\Dispatcher;
use TimeTracking\Output\Json;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    /** @var Json */
    protected $json;

    public function setUp()
    {
        $this->json = new Json();
    }

    /**
     * @param array $result
     */
    protected function checkJsonValid($result)
    {
        $decoded = json_decode($result, true);
        $this->assertInternalType('array', $decoded);
        $this->assertNotEmpty($decoded);
    }

    public function formatOutputDataProvider()
    {
        return [
            // Simple types
            ['foo', '"result":"foo"'],
            [false, '"result":false'],
            [null, '"result":null'],

            // Complex types
            [['foo' => 'bar'], '"result":{"foo":"bar"}'],
            [[1, 2, 3], '"result":[1,2,3]'],
        ];
    }

    /**
     * @dataProvider formatOutputDataProvider
     *
     * @param mixed  $output
     * @param string $expectedResult
     */
    public function testFormatOutput($output, $expectedResult)
    {
        $result = $this->json->formatOutput(new Dispatcher([]), $output);

        $this->assertContains($expectedResult, $result);
        $this->assertCount(1, explode("\n", $result), 'The result should only contain one line because pretty print is not active!');
        $this->checkJsonValid($result);
    }

    public function testFormatOutputPrettyPrint()
    {
        $dispatcher = new Dispatcher(['pretty' => 1]);
        $result = $this->json->formatOutput($dispatcher, ['foo' => 'bar']);

        $this->assertGreaterThan(1, count(explode("\n", $result)), 'The result should contain more than one line because pretty print is activated');
        $this->checkJsonValid($result);

        $json = json_decode($result, true);
        $this->assertArrayHasKey('success', $json);
        $this->assertTrue($json['success']);
    }

    public function testFormatError()
    {
        $error = new \Exception('PHPUnit_Test_Error');
        $result = $this->json->formatError(new Dispatcher([]), $error);

        $this->assertContains('PHPUnit_Test_Error', $result);
        $this->assertCount(1, explode("\n", $result), 'The result should only contain one line because pretty print is not active!');
        $this->checkJsonValid($result);

        $json = json_decode($result, true);
        $this->assertArrayHasKey('success', $json);
        $this->assertFalse($json['success']);
    }

    public function testFormatErrorPrettyPrint()
    {
        $error = new \Exception('PHPUnit_Test_Error');
        $dispatcher = new Dispatcher(['pretty' => 1]);
        $result = $this->json->formatError($dispatcher, $error);

        $this->assertContains('PHPUnit_Test_Error', $result);
        $this->assertGreaterThan(1, count(explode("\n", $result)), 'The result should contain more than one line because pretty print is activated');
        $this->checkJsonValid($result);

        $json = json_decode($result, true);
        $this->assertArrayHasKey('success', $json);
        $this->assertFalse($json['success']);
    }
}
