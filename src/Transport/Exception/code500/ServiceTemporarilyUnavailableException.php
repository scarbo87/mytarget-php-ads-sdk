<?php

namespace scarbo87\RestApiSdk\Transport\Exception\code500;

use scarbo87\RestApiSdk\Exception\ApiException;

class ServiceTemporarilyUnavailableException extends ServerErrorException
    implements ApiException
{
}
