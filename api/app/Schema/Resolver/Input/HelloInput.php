<?php

namespace Library\Schema\Resolver\Input;

use Library\Schema\Resolver\InputResolver;

class HelloInput extends InputResolver
{
    protected string $firstName;
    protected string $lastName;

    public function withArgs(array $args): static
    {
        $this->firstName = $args['firstName'] ?? null;
        $this->lastName = $args['lastName'] ?? null;
        return $this;
    }

}