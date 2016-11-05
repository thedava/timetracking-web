<?php

namespace TimeTrackingTest;


use TimeTracking\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    protected $config;

    /** @var array */
    protected $locations;

    protected function setUp()
    {
        $this->locations = Config::getLocations();
        Config::setLocations([
            '/test/config/*.php',
        ]);

        $this->config = Config::get(true);
    }

    protected function tearDown()
    {
        Config::setLocations($this->locations);
        Config::get(true);
    }

    public function testMerge()
    {
        // Two keys from two files on the first level
        $this->assertArrayHasKey('test1', $this->config);
        $this->assertArrayHasKey('test2', $this->config);
    }

    public function testDeepMerge()
    {
        // One key present in two files with multiple sub keys
        $this->assertArrayHasKey('test', $this->config);
        $this->assertInternalType('array', $this->config['test']);
        $this->assertArrayHasKey('testKey1', $this->config['test']);
        $this->assertArrayHasKey('testKey2', $this->config['test']);
    }
}
