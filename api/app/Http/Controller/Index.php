<?php

namespace Library\Http\Controller;

use Psr\Http\Message\RequestInterface as Request;
use Library\Http\Message\Response;

class Index implements Controller
{
    public function __invoke(Request $request, Response $response): Response {
        $response->getBody()->write('hi');
        $response = $response->withJSON(['hellow' => '22']);

        return $response;
    }
}