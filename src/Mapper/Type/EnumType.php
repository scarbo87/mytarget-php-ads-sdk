<?php

namespace scarbo87\RestApiSdk\Mapper\Type;

use scarbo87\RestApiSdk\Domain\AbstractEnum;
use scarbo87\RestApiSdk\Mapper\Mapper;

class EnumType implements Type
{
    /**
     * @inheritdoc
     */
    public function hydrated($value, $type, Mapper $mapper)
    {
        if (AbstractEnum::isValidValueForClass($type, $value)) {
            return AbstractEnum::fromValueForClass($type, $value);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function snapshot($value, $type, Mapper $mapper)
    {
        if ( ! $value instanceof AbstractEnum) {
            return null;
        }

        return $value->getValue();
    }
}
