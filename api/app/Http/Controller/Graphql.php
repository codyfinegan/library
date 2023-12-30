<?php

namespace Library\Http\Controller;

use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Schema;
use Psr\Http\Message\RequestInterface as Request;
use Library\Http\Message\Response;

class Graphql implements Controller
{
    protected Schema $schema;

    /**
     * @param Schema $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }


    public function __invoke(Request $request, Response $response): Response {

        $config = ServerConfig::create()
            ->setSchema($this->schema)
//            ->setErrorFormatter($myFormatter)
            ->setDebugFlag(true)
        ;

        $server = new StandardServer($config);
        $result = $server->executePsrRequest($request);

        return $response->withJSON($result);
    }
}