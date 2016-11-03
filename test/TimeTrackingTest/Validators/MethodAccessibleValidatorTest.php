<?php

namespace TimeTrackingTest\Validators;


use TimeTracking\Validators\MethodAccessibleValidator;
use TimeTrackingTest\Mock\Object\AccessibleObject;

class MethodAccessibleValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var MethodAccessibleValidator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new MethodAccessibleValidator();
    }

    /**
     * @param $methodName
     *
     * @return \ReflectionMethod
     */
    protected function getMethod($methodName)
    {
        $refClass = new \ReflectionClass(AccessibleObject::class);
        return $refClass->getMethod($methodName);
    }

    public function testIsValidWithInvalidInput()
    {
        $this->assertFalse($this->validator->isValid(null));
        $this->assertFalse($this->validator->isValid(true));
        $this->assertFalse($this->validator->isValid(new \stdClass()));
        $this->assertFalse($this->validator->isValid(AccessibleObject::class));
    }

    public function testIsValidWithNonPublicAndAbstractMethods()
    {
        $this->assertFalse($this->validator->isValid($this->getMethod('protectedMethod')));
        $this->assertFalse($this->validator->isValid($this->getMethod('privateMethod')));
        $this->assertFalse($this->validator->isValid($this->getMethod('abstractMethod'))); // public but abstract
    }

    public function testIsValidWithConstructorAndDestructor()
    {
        $this->assertFalse($this->validator->isValid($this->getMethod('__construct')));
        $this->assertFalse($this->validator->isValid($this->getMethod('__destruct')));
    }

    public function testIsValid()
    {
        $this->assertTrue($this->validator->isValid($this->getMethod('publicMethod')));
    }
}
