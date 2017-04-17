<?php

namespace scarbo87\RestApiSdk\Transport;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory
{
    /**
     * @var array
     */
    protected $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * @var UriInterface
     */
    protected $baseAddress;

    public function __construct(UriInterface $baseAddress)
    {
        $this->baseAddress = $baseAddress;
    }

    /**
     * @param array $headers
     */
    public function setDefaultHeaders(array $headers)
    {
        $this->defaultHeaders = $headers;
    }

    /**
     * @param string     $method
     * @param string     $path
     * @param array|null $query
     * @param array|null $headers
     *
     * @return RequestInterface
     */
    public function create($method, $path, array $query = null, array $headers = null)
    {
        $uri = $this->baseAddress->withPath($path);

        if (null !== $query) {
            $uri = $uri->withQuery(http_build_query($query));
        }
        if (null === $headers) {
            $headers = $this->defaultHeaders;
        }

        return new Request($method, $uri, $headers);
    }
}
