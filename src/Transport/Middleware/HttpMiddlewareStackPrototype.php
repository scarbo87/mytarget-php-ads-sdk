<?php

namespace scarbo87\RestApiSdk\Transport\Middleware;

use scarbo87\RestApiSdk\Transport\HttpTransportInterface;

class HttpMiddlewareStackPrototype extends HttpMiddlewareStack
{
    /**
     * @param HttpMiddlewareInterface[] $middlewares
     * @param HttpTransportInterface    $http
     *
     * @return HttpMiddlewareStackPrototype
     */
    public static function fromArray(array $middlewares, HttpTransportInterface $http)
    {
        $stack = new \SplStack();
        array_map([$stack, 'push'], $middlewares);

        return new HttpMiddlewareStackPrototype($stack, $http);
    }

    /**
     * @param HttpTransportInterface $http
     *
     * @return HttpMiddlewareStackPrototype
     */
    public static function newEmpty(HttpTransportInterface $http)
    {
        return new HttpMiddlewareStackPrototype(new \SplStack(), $http);
    }

    /**
     * @return HttpMiddlewareStack
     */
    public function freeze()
    {
        return new HttpMiddlewareStack(clone $this->middlewares, $this->http);
    }

    /**
     * @param HttpMiddlewareInterface $middleware
     */
    public function push(HttpMiddlewareInterface $middleware)
    {
        $this->middlewares->push($middleware);
    }
}
