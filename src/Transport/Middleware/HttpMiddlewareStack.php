<?php

namespace scarbo87\RestApiSdk\Transport\Middleware;

use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\HttpTransportInterface;
use scarbo87\RestApiSdk\Transport\Middleware\Impl\TerminatingMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpMiddlewareStack
{
    /**
     * @var \SplStack|HttpMiddlewareInterface[]
     */
    protected $middlewares;

    /**
     * @var HttpTransportInterface
     */
    protected $http;

    /**
     * @param \SplStack|HttpMiddlewareInterface[] $middlewares
     * @param HttpTransportInterface              $http
     */
    public function __construct(\SplStack $middlewares, HttpTransportInterface $http)
    {
        $this->middlewares = clone $middlewares;
        $this->http = $http;
    }

    /**
     * @param RequestInterface $request
     * @param Context $context
     *
     * @return ResponseInterface
     */
    public function request(RequestInterface $request, Context $context)
    {
        return $this->pop()->request($request, $this, $context);
    }

    /**
     * @return HttpMiddlewareInterface
     */
    private function pop()
    {
        return $this->middlewares->isEmpty() ? new TerminatingMiddleware($this->http) : $this->middlewares->pop();
    }
}
