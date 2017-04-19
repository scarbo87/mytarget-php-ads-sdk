<?php

namespace tests\scarbo87\RestApiSdk\Transport\Middleware;

use scarbo87\RestApiSdk\Context;
use GuzzleHttp\Psr7\Response;
use scarbo87\RestApiSdk\Transport\HttpTransportInterface;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareInterface;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStack;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpMiddlewareStackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|HttpMiddlewareInterface
     */
    protected $middleware;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|HttpTransportInterface
     */
    protected $http;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RequestInterface
     */
    protected $request;

    protected function setUp()
    {
        $this->middleware = $this->getMockForAbstractClass(HttpMiddlewareInterface::class, [], "", false);
        $this->http = $this->getMockForAbstractClass(HttpTransportInterface::class, [], "", false);
        $this->request = $this->getMockForAbstractClass(RequestInterface::class, [], "", false);
    }

    public function testRequestCallsMiddleware()
    {
        $middlewares = new \SplStack();
        $middlewares->push($this->middleware);

        $response = $this->getMockForAbstractClass(ResponseInterface::class, [], "", false);
        $stack = new HttpMiddlewareStack($middlewares, $this->http);

        $this->middleware->expects($this->once())->method("request")
            ->with($this->identicalTo($this->request))
            ->willReturnCallback(function (RequestInterface $request, HttpMiddlewareStack $stack) {
                return $stack->request($request, new Context());
            });

        $this->http->expects($this->once())->method("request")
            ->with($this->identicalTo($this->request))
            ->willReturn($response);

        $this->assertSame($response, $stack->request($this->request, new Context()), "Result assertion");
    }

    public function testRequestCallsManyMiddlewares()
    {
        $finalMiddleware = $this->getMockForAbstractClass(HttpMiddlewareInterface::class, [], "", false);

        $middlewares = new \SplStack();
        $middlewares->push($finalMiddleware);
        $middlewares->push($this->middleware);

        $response = new Response();
        $resultingResponse = $response->withHeader("X-Added-In-Final", "foo");
        $stack = new HttpMiddlewareStack($middlewares, $this->http);
        $ctx = new Context();

        $this->middleware->expects($this->once())->method("request")
            ->with($this->identicalTo($this->request))
            ->willReturnCallback(function (RequestInterface $request, HttpMiddlewareStack $stack, Context $ctx) {
                $ctx->addParameter("off", "to final");
                $res = $stack->request($request, $ctx);

                $this->assertSame($res->getHeaderLine("X-Added-In-Final"), "foo", "Check header added in the next middleware");

                return $res;
            });

        $finalMiddleware->expects($this->once())->method("request")
            ->with($this->identicalTo($this->request))
            ->willReturnCallback(function (RequestInterface $request, HttpMiddlewareStack $stack, Context $context) {
                $this->assertSame(["off" => "to final"], $context->getParameters(), "check context added in the prev middleware");

                $res = $stack->request($request, $context);

                return $res->withHeader("X-Added-In-Final", "foo");
            });

        $this->http->expects($this->once())->method("request")
            ->with($this->identicalTo($this->request), $this->identicalTo($ctx))
            ->willReturn($response);

        $this->assertEquals($resultingResponse, $stack->request($this->request, $ctx));
    }
}
