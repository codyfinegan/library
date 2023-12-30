<?php

namespace Library\Http\Middleware;

use Library\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonResponse implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Response $response */
        $response = $handler->handle($request);

        if ($response->getBody()->getSize() && $response->isJSON()) {
            return $response->withHeader('Content-Type', 'application/json');
        }

        return $response;
    }
}