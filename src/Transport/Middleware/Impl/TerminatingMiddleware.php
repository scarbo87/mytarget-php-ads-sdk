<?php

namespace scarbo87\RestApiSdk\Transport\Middleware\Impl;

use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\HttpTransport;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddleware;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStack;
use Psr\Http\Message\RequestInterface;

class TerminatingMiddleware implements HttpMiddleware
{
    /**
     * @var HttpTransport
     */
    private $http;

    public function __construct(HttpTransport $http)
    {
        $this->http = $http;
    }

    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, HttpMiddlewareStack $stack, Context $context)
    {
        return $this->http->request($request, $context);
    }
}
