<?php

namespace scarbo87\RestApiSdk;

use scarbo87\RestApiSdk\Exception\SdkException;
use GuzzleHttp\Psr7 as psr;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStackPrototype;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use scarbo87\RestApiSdk\Transport\RequestFactoryInterface;

class Client
{
    /**
     * @var RequestFactoryInterface
     */
    protected $requestFactory;
    /**
     * @var HttpMiddlewareStackPrototype
     */
    protected $http;

    /**
     * @param RequestFactoryInterface      $requestFactory
     * @param HttpMiddlewareStackPrototype $httpStack
     */
    public function __construct(RequestFactoryInterface $requestFactory, HttpMiddlewareStackPrototype $httpStack)
    {
        $this->requestFactory = $requestFactory;
        $this->http = $httpStack;
    }

    /**
     * Makes GET request and returns response
     *
     * @param string       $path
     * @param array|null   $query
     * @param Context|null $context
     *
     * @return ResponseInterface
     */
    public function get($path, array $query = null, Context $context = null)
    {
        $request = $this->requestFactory->create('GET', $path, $query);

        return $this->http->freeze()->request($request, $context ?: new Context());
    }

    /**
     * Makes POST request and returns response
     *
     * @param string       $path
     * @param array|null   $query
     * @param array|null   $body
     * @param Context|null $context
     *
     * @return ResponseInterface
     */
    public function post($path, array $query = null, $body = null, Context $context = null)
    {
        $request = $this->requestFactory->create('POST', $path, $query);
        if ($body !== null) {
            /** @var RequestInterface $request */
            $request = $request->withBody(psr\stream_for(json_encode($body)));
        }

        return $this->http->freeze()->request($request, $context ?: new Context());
    }

    /**
     * Makes PUT request and returns response
     *
     * @param string       $path
     * @param array|null   $query
     * @param array|null   $body
     * @param Context|null $context
     *
     * @return ResponseInterface
     */
    public function put($path, array $query = null, $body = null, Context $context = null)
    {
        $request = $this->requestFactory->create('PUT', $path, $query);
        if ($body !== null) {
            /** @var RequestInterface $request */
            $request = $request->withBody(psr\stream_for(json_encode($body)));
        }

        return $this->http->freeze()->request($request, $context ?: new Context());
    }

    /**
     * Makes PATCH request and returns response
     *
     * @param string       $path
     * @param array|null   $query
     * @param array|null   $body
     * @param Context|null $context
     *
     * @return ResponseInterface
     */
    public function patch($path, array $query = null, $body = null, Context $context = null)
    {
        $request = $this->requestFactory->create('PATCH', $path, $query);
        if ($body !== null) {
            /** @var RequestInterface $request */
            $request = $request->withBody(psr\stream_for(json_encode($body)));
        }

        return $this->http->freeze()->request($request, $context ?: new Context());
    }

    /**
     * Makes DELETE request and returns response
     *
     * @param string       $path
     * @param array|null   $query
     * @param Context|null $context
     *
     * @return ResponseInterface
     * @throws SdkException
     */
    public function delete($path, array $query = null, Context $context = null)
    {
        $request = $this->requestFactory->create('DELETE', $path, $query);

        return $this->http->freeze()->request($request, $context ?: new Context());
    }

    /**
     * @param string       $path
     * @param array        $body
     * @param array|null   $query
     * @param Context|null $context
     *
     * @return ResponseInterface
     * @throws SdkException
     */
    public function postMultipart($path, array $body, array $query = null, Context $context = null)
    {
        $request = $this->requestFactory->create('POST', $path, $query);
        $request = $request->withBody(new psr\MultipartStream($body));

        return $this->http->freeze()->request($request, $context ?: new Context());
    }
}
