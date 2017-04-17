<?php

namespace scarbo87\RestApiSdk\Transport\Exception\code500;

use scarbo87\RestApiSdk\Exception\ApiException;
use scarbo87\RestApiSdk\Transport\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ServerErrorException extends RequestException
    implements ApiException
{
    /**
     * @param RequestInterface  $req
     * @param ResponseInterface $res
     *
     * @return ServerErrorException
     */
    public static function fromResponse(RequestInterface $req, ResponseInterface $res)
    {
        $message = "Sdk: {$res->getStatusCode()} Server Error";
        switch ($res->getStatusCode()) {
            case 503:
                return new ServiceTemporarilyUnavailableException($message, $req, $res);
            default:
                return new ServerErrorException($message, $req, $res);
        }
    }
}
