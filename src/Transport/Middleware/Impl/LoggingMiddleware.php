<?php

namespace scarbo87\RestApiSdk\Transport\Middleware\Impl;

use function GuzzleHttp\Psr7\str;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use scarbo87\RestApiSdk\Context;
use scarbo87\RestApiSdk\Transport\Exception\RequestException;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareInterface;
use scarbo87\RestApiSdk\Transport\Middleware\HttpMiddlewareStack;

class LoggingMiddleware implements HttpMiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, HttpMiddlewareStack $stack, Context $context)
    {
        try {
            $response = $stack->request($request, $context);
            $this->log($request, $response);
            return $response;
        } catch (RequestException $e) {
            $this->logError($request, $e);
            throw $e;
        }
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    protected function log(RequestInterface $request, ResponseInterface $response)
    {
        $this->logger->debug('Request: ' . str($request));
        $this->logger->debug('Response: ' . str($response));
    }

    /**
     * @param RequestInterface $request
     * @param RequestException $exception
     */
    protected function logError(RequestInterface $request, RequestException $exception)
    {
        $this->logger->debug('Request: ' . str($request));
        $this->logger->error('Request exception: ' . $exception->getMessage(), ['exception' => $exception]);
    }
}