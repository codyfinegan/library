<?php

namespace Library\Schema\Resolver;

interface QueryResolver
{
    /**
     * @param array|InputResolver|mixed $args
     * @param $context
     * @return mixed
     */
    public function resolve(array $args, $context): mixed;
}