<?php

namespace tests\scarbo87\RestApiSdk\Transport\Middleware\Impl;

use scarbo87\RestApiSdk\Context;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception as guzzleEx;
use GuzzleHttp\Psr7 as psr;
use scarbo87\RestApiSdk\Client;
use scarbo87\RestApiSdk\Transport\Exception as ex;
use scarbo87\RestApiSdk\Transport\GuzzleHttpTransport;
use scarbo87\RestApiSdk\Transport\HttpTransport;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStackPrototype;
use scarbo87\RestApiSdk\Transport\RequestFactory;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestSuccess()
    {
        /** @var HttpTransport|PHPUnit_Framework_MockObject_MockObject $http */
        $http = $this->createMock(GuzzleHttpTransport::class);

        $http
            ->expects(self::once())
            ->method('request')
            ->willReturnCallback(
                function () {
                    return $this->createMock(ResponseInterface::class);
                }
            );

        $client = new Client(
            new RequestFactory(new psr\Uri('https://example.com')),
            HttpMiddlewareStackPrototype::newEmpty($http)
        );

        self::assertNotEmpty($client->get('/any/path', null, new Context()));
    }

    public function testRequestTransportException()
    {
        /** @var ClientInterface|PHPUnit_Framework_MockObject_MockObject $guzzle */
        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->method('send')
               ->willThrowException(new guzzleEx\ClientException('', $this->createMock(RequestInterface::class)));

        $client = new Client(
            new RequestFactory(new psr\Uri('https://example.com')),
            HttpMiddlewareStackPrototype::newEmpty(new GuzzleHttpTransport($guzzle))
        );

        $this->setExpectedException(ex\NetworkException::class);

        $client->get('/any/path', null, new Context());
    }
}
