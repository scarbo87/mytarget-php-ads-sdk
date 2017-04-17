<?php

namespace tests\scarbo87\RestApiSdk\Mapper\Type;

use scarbo87\RestApiSdk\Mapper\Annotation\Field;

class ObjectStub extends ObjectParentStub
{
    /**
     * @var int
     * @Field(type="int")
     */
    private $integerValue;

    /**
     * ObjectStub constructor.
     *
     * @param int $integerValue
     * @param string $stringValue
     */
    public function __construct($integerValue, $stringValue)
    {
        $this->integerValue = $integerValue;
        parent::__construct($stringValue);
    }

    /**
     * @return int
     */
    public function getIntegerValue()
    {
        return $this->integerValue;
    }
}
