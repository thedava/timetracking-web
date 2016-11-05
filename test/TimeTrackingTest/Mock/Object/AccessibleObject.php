<?php

namespace TimeTrackingTest\Mock\Object;

abstract class AccessibleObject
{
    abstract public function abstractMethod();

    public function publicMethod()
    {
        return true;
    }

    protected function protectedMethod()
    {
        return true;
    }

    private function privateMethod()
    {
        return true;
    }

    public function __construct()
    {
        // Only to avoid "unused method" warnings from IDE
        $this->publicMethod() && $this->protectedMethod() && $this->privateMethod();
    }

    protected function __destruct()
    {
    }
}
