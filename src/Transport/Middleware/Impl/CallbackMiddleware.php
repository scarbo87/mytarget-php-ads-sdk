<?php

namespace scarbo87\RestApiSdk\Transport\Middleware\Impl;

use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareInterface;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStack;
use Psr\Http\Message\RequestInterface;

class CallbackMiddleware implements HttpMiddlewareInterface
{
    /**
     * @var callable callable(RequestInterface $request, HttpMiddlewareStack $stack, Context $context = null): ResponseInterface
     */
    private $callback;

    /**
     * @param callable $callback callable(RequestInterface $request, HttpMiddlewareStack $stack, Context $context = null): ResponseInterface
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, HttpMiddlewareStack $stack, Context $context)
    {
        return call_user_func($this->callback, $request, $stack, $context);
    }
}
