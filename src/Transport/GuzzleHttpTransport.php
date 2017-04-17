<?php

namespace scarbo87\RestApiSdk\Transport;

use scarbo87\RestApiSdk\Context;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Exception as guzzleEx;
use scarbo87\RestApiSdk\Transport\Exception as sdkEx;

/**
 * An implementation of HttpTransport that uses Guzzle and depends on "guzzlehttp/guzzle" composer package
 */
class GuzzleHttpTransport implements HttpTransport
{
    /**
     * @var ClientInterface
     */
    private $guzzle;

    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, Context $context)
    {
        try {
            return $this->guzzle->send($request, ["http_errors" => false]);
        } catch (guzzleEx\GuzzleException $e) {
            if ($e instanceof guzzleEx\RequestException) {
                $response = $e->getResponse();
            } else {
                $response = null;
            }

            throw new sdkEx\NetworkException($e->getMessage(), $request, $response, $e);
        }
    }
}
