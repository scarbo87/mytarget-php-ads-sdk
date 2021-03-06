<?php

namespace scarbo87\RestApiSdk\Transport\Middleware;

use scarbo87\RestApiSdk\Context;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpMiddlewareInterface
{
    /**
     * The middleware can decide whether it needs to call the next middleware
     * from $stack or whether it can process $request itself and return ResponseInterface
     * directly.
     *
     * Also you can change the $request before passing it to the next middleware,
     * as well as ResponseInterface returned from the next middleware before returning it
     * to the upper frame.
     *
     * To pass the $request further you need to call $stack->request($request), it will
     * return ResponseInterface instance or throw exception both of which you can choose
     * to propagate further up the stack or replace with your own response/exception.
     *
     * @param RequestInterface $request
     * @param HttpMiddlewareStack $stack
     * @param Context $context
     *
     * @return ResponseInterface
     */
    public function request(RequestInterface $request, HttpMiddlewareStack $stack, Context $context);
}
