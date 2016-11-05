<?php

namespace TimeTrackingTest\Core;

use TimeTracking\Core\ReflectionObject;

class ReflectionObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testGetObject()
    {
        $object = new \stdClass();
        $object->{'id'} = __METHOD__;

        $refObject = new ReflectionObject($object);

        $this->assertSame($refObject->getObject(), $object);
        $this->assertEquals($refObject->getObject(), $object);
        $this->assertTrue($refObject->getObject() === $object);
    }
}
