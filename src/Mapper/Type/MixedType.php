<?php

namespace scarbo87\RestApiSdk\Mapper\Type;

use scarbo87\RestApiSdk\Mapper\Mapper;

class MixedType implements Type
{
    /**
     * @inheritdoc
     */
    public function hydrated($value, $type, Mapper $mapper)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function snapshot($value, $type, Mapper $mapper)
    {
        return $value;
    }
}
