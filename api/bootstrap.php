<?php

use GraphQL\Type\Schema;
use Library\Http\Message\ResponseFactory;
use Library\Schema\Resolver as GraphQLResolver;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    ResponseFactoryInterface::class => DI\create(ResponseFactory::class),

    Schema::class => DI\factory([GraphQLResolver::class, 'load'])
];
