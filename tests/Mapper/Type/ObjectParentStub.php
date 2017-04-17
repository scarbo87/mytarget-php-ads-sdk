<?php

namespace tests\scarbo87\RestApiSdk\Mapper\Type;

use scarbo87\RestApiSdk\Mapper\Annotation\Field;

class ObjectParentStub
{
    /**
     * @var string
     * @Field(type="string")
     */
    private $stringValue;

    /**
     * ObjectParentStub constructor.
     *
     * @param $stringValue
     */
    public function __construct($stringValue)
    {
        $this->stringValue = $stringValue;
    }

    /**
     * @return string
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }
}
