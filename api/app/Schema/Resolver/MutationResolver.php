<?php

namespace Library\Schema\Resolver;

interface MutationResolver
{
    /**
     * @param array $args
     * @param $context
     * @return mixed
     */
    public function resolve(array $args, $context): mixed;
}