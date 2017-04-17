<?php

namespace scarbo87\RestApiSdk\Transport\Exception\code300;

use scarbo87\RestApiSdk\Exception\ApiException;
use scarbo87\RestApiSdk\Transport\Exception\RequestException;

class RedirectUnexpectedException extends RequestException
    implements ApiException
{
}
