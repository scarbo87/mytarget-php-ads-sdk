<?php

namespace scarbo87\RestApiSdk\Mapper\Exception;

use scarbo87\RestApiSdk\Exception\SdkException;

class ContextAwareException extends \LogicException
    implements SdkException
{
    public function __construct($inClass, $inField, \Exception $previous)
    {
        $message = sprintf("%s::%s - %s", $inClass, $inField, $previous->getMessage());

        parent::__construct($message, 0, $previous);
    }
}
