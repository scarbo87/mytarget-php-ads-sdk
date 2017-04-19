<?php

namespace scarbo87\RestApiSdk\Transport;

use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    /**
     * Create Request
     *
     * @param string     $method
     * @param string     $path
     * @param array|null $query
     * @param array|null $headers
     *
     * @return RequestInterface
     */
    public function create($method, $path, array $query = null, array $headers = null);
}