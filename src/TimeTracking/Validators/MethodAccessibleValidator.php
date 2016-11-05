<?php

namespace TimeTracking\Validators;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class MethodAccessibleValidator extends AbstractValidator
{
    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param mixed $value
     *
     * @throws Exception\RuntimeException If validation of $value is impossible
     *
     * @return bool
     */
    public function isValid($value)
    {
        // We need a reflection method object here
        if (!$value instanceof \ReflectionMethod) {
            return false;
        }

        // Method is definitive not accessible
        if (!$value->isPublic() || $value->isAbstract()) {
            return false;
        }

        // Method is accessible but not valid
        if ($value->isConstructor() || $value->isDestructor()) {
            return false;
        }

        return true;
    }
}
