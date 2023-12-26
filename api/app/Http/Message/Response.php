<?php

namespace Library\Http\Message;

class Response extends \Slim\Psr7\Response
{

    protected ?array $json = null;

    /**
     * @param array $payload
     * @return $this
     */
    public function withJSON(array $payload): static
    {
        $response = clone $this;
        $response->json = $payload;
        $response->getBody()->write(json_encode($payload));

        return $response;
    }

    public function isJSON(): bool
    {
        return $this->json !== null;
    }

}