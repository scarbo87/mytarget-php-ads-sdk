<?php

namespace scarbo87\RestApiSdk\Domain\Exception;

class InvalidEnumValueException extends \InvalidArgumentException
{
    /**
     * @param string $value
     * @param string $enumClass
     *
     * @return static
     */
    public static function create($value, $enumClass)
    {
        return new static(sprintf('Value "%s" does not exist in %s', $value, $enumClass));
    }
}
