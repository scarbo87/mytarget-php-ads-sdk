<?php

namespace scarbo87\RestApiSdk\Transport;

use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\Exception\NetworkException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpTransport
{
    /**
     * @param RequestInterface $request
     * @param Context $context
     *
     * @return ResponseInterface
     * @throws NetworkException
     */
    public function request(RequestInterface $request, Context $context);
}
