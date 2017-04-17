<?php

namespace scarbo87\RestApiSdk\Mapper\Exception;

use scarbo87\RestApiSdk\Exception\SdkException;

class ClassNotFoundException extends \LogicException
    implements SdkException, ContextUnawareException
{
    /**
     * @var string
     */
    public $class;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        parent::__construct(sprintf("Class %s not found", $class));

        $this->class = $class;
    }
}
