<?php

namespace scarbo87\RestApiSdk\Transport\Middleware\Impl;

use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\HttpTransportInterface;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareInterface;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStack;
use Psr\Http\Message\RequestInterface;

/**
 * @internal
 */
class TerminatingMiddleware implements HttpMiddlewareInterface
{
    /**
     * @var HttpTransportInterface
     */
    private $http;

    public function __construct(HttpTransportInterface $http)
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
