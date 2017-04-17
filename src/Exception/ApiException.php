<?php

namespace scarbo87\RestApiSdk\Exception;

/**
 * All exceptions that happened because of our or API's fault (excluding network failures)
 */
interface ApiException extends SdkException
{
}
