<?php

namespace Library\Http\Message;

use JsonSerializable;

class Response extends \Slim\Psr7\Response
{

    protected ?JsonSerializable $json = null;

    /**
     * @param JsonSerializable $payload
     * @return $this
     */
    public function withJSON(JsonSerializable $payload): static
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