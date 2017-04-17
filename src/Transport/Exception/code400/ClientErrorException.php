<?php

namespace scarbo87\RestApiSdk\Transport\Exception\code400;

use scarbo87\RestApiSdk\Exception\ApiException;
use scarbo87\RestApiSdk\Transport\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientErrorException extends RequestException
    implements ApiException
{
    /**
     * @param RequestInterface  $req
     * @param ResponseInterface $res
     *
     * @return ClientErrorException
     */
    public static function fromResponse(RequestInterface $req, ResponseInterface $res)
    {
        $message = "Sdk: {$res->getStatusCode()} Client Error";
        switch ($res->getStatusCode()) {
            case 401:
                return new UnauthorizedException($message, $req, $res);
            case 403:
                return new AccessForbiddenException($message, $req, $res);
            case 404:
                return new NotFoundException($message, $req, $res);
            case 405:
                return new MethodNotAllowedException($message, $req, $res);
            case 415:
                return new UnsupportedMediaTypeException($message, $req, $res);
            case 429:
                return new TooManyRequestsException($message, $req, $res);
            default:
                return new ClientErrorException($message, $req, $res);
        }
    }
}
