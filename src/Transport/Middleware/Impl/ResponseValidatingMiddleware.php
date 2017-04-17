<?php

namespace scarbo87\RestApiSdk\Transport\Middleware\Impl;

use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\Exception\code400\ClientErrorException;
use scarbo87\RestApiSdk\Transport\Exception\code500\ServerErrorException;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddleware;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStack;
use Psr\Http\Message\RequestInterface;

class ResponseValidatingMiddleware implements HttpMiddleware
{
    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, HttpMiddlewareStack $stack, Context $context)
    {
        $response = $stack->request($request, $context);
        $code = $response->getStatusCode();

        if ($code >= 500 && $code < 600) {
            throw ServerErrorException::fromResponse($request, $response);
        }
        if ($code >= 400 && $code < 500) {
            throw ClientErrorException::fromResponse($request, $response);
        }

        return $response;
    }
}
