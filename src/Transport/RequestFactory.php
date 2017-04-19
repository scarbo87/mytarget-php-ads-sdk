<?php

namespace scarbo87\RestApiSdk\Transport;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * @var array
     */
    protected $defaultHeaders;

    /**
     * @var UriInterface
     */
    protected $baseAddress;

    /**
     * @param UriInterface $baseAddress
     * @param array        $headers
     */
    public function __construct(UriInterface $baseAddress, array $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'])
    {
        $this->baseAddress = $baseAddress;
        $this->defaultHeaders = $headers;
    }

    /**
     * @inheritdoc
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
