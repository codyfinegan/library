<?php

namespace Library\Http\Controller;

use Library\Http\Message\Response;
use Psr\Http\Message\RequestInterface as Request;
use Psr\SimpleCache\CacheInterface;

class Index implements Controller
{
    public function __invoke(Request $request, Response $response, CacheInterface $cache): Response
    {
        $response->getBody()->write('Hi');

        return $response;
    }
}