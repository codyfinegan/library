<?php

namespace Library\Schema\Resolver;

abstract class InputResolver
{
    /**
     * @param array $args
     * @return $this
     * @internal
     */
    abstract public function withArgs(array $args): static;

    public function __get(string $name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }

        return null;
    }
}