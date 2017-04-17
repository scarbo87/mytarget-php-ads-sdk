<?php

namespace scarbo87\RestApiSdk;

use scarbo87\RestApiSdk\Exception\SdkException;
use GuzzleHttp\Psr7 as psr;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStackPrototype;
use scarbo87\RestApiSdk\Transport\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var HttpMiddlewareStackPrototype
     */
    private $http;

    public function __construct(RequestFactory $requestFactory, HttpMiddlewareStackPrototype $httpStack)
    {
        $this->requestFactory = $requestFactory;
        $this->http = $httpStack;
    }

    /**
     * Makes GET request and returns response
     *
     * @param string     $path
     * @param array|null $query
     * @param Context    $context
     *
     * @return ResponseInterface
     */
    public function get($path, array $query = null, Context $context)
    {
        $request = $this->requestFactory->create('GET', $path, $query);

        return $this->http->freeze()->request($request, $context);
    }

    /**
     * Makes POST request and returns response
     *
     * @param string     $path
     * @param array|null $query
     * @param array|null $body
     * @param Context    $context
     *
     * @return ResponseInterface
     */
    public function post($path, array $query = null, $body = null, Context $context)
    {
        $request = $this->requestFactory->create('POST', $path, $query);
        if ($body !== null) {
            /** @var RequestInterface $request */
            $request = $request->withBody(psr\stream_for(json_encode($body)));
        }

        return $this->http->freeze()->request($request, $context);
    }

    /**
     * Makes PUT request and returns response
     *
     * @param string     $path
     * @param array|null $query
     * @param array|null $body
     * @param Context    $context
     *
     * @return ResponseInterface
     */
    public function put($path, array $query = null, $body = null, Context $context)
    {
        $request = $this->requestFactory->create('PUT', $path, $query);
        if ($body !== null) {
            /** @var RequestInterface $request */
            $request = $request->withBody(psr\stream_for(json_encode($body)));
        }

        return $this->http->freeze()->request($request, $context);
    }

    /**
     * Makes DELETE request and returns response
     *
     * @param string     $path
     * @param array|null $query
     * @param Context    $context
     *
     * @return ResponseInterface
     * @throws SdkException
     */
    public function delete($path, array $query = null, Context $context)
    {
        $request = $this->requestFactory->create('DELETE', $path, $query);

        return $this->http->freeze()->request($request, $context);
    }

    /**
     * @param string     $path
     * @param array      $body
     * @param array|null $query
     * @param Context    $context
     *
     * @return ResponseInterface
     * @throws SdkException
     */
    public function postMultipart($path, array $body, array $query = null, Context $context)
    {
        $request = $this->requestFactory->create('POST', $path, $query);
        $request = $request->withBody(new psr\MultipartStream($body));

        return $this->http->freeze()->request($request, $context);
    }
}
