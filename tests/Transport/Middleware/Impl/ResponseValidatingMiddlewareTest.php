<?php

namespace tests\scarbo87\RestApiSdk\Transport\Middleware\Impl;

use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\Exception\code400\ClientErrorException;
use scarbo87\RestApiSdk\Transport\Exception\code500\ServerErrorException;
use GuzzleHttp\Psr7\Request;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStack;
use scarbo87\RestApiSdk\Transport\Middleware\Impl\ResponseValidatingMiddleware;
use Psr\Http\Message\ResponseInterface;

class ResponseValidatingMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testDataProvider()
    {
        return [
            '500' => [500, ServerErrorException::class],
            '400' => [400, ClientErrorException::class],
            '200' => [200, null],
            '300' => [300, null],
        ];
    }

    /**
     * @dataProvider testDataProvider
     *
     * @param int         $code
     * @param string|null $exception
     */
    public function testRequestStatusCode($code, $exception)
    {
        $request = new Request('GET', '/', ['X-Phpunit' => ['a', 'b']], 'some request data');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
                 ->willReturn($code);

        /** @var HttpMiddlewareStack|\PHPUnit_Framework_MockObject_MockObject $stack */
        $stack = $this->createMock(HttpMiddlewareStack::class);

        $stack->expects(self::once())
              ->method('request')
              ->with($request)
              ->willReturn($response);

        if ($exception !== null) {
            $this->setExpectedException($exception);
        }

        $middleware = new ResponseValidatingMiddleware();
        $middleware->request($request, $stack, new Context());
    }
}
