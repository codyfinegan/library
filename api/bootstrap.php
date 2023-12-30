<?php

use DI\Container;
use GraphQL\Type\Schema;
use Library\Config;
use Library\Http\Message\ResponseFactory;
use Library\Schema\Resolver as GraphQLResolver;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\SimpleCache\CacheInterface;

return [
    'pathRoot' => DI\string(__DIR__),
    'pathApp' => DI\string('{pathRoot}/app'),
    'pathConfig' => DI\string('{pathRoot}/config'),

    ResponseFactoryInterface::class => DI\create(ResponseFactory::class),

    Schema::class => DI\factory([GraphQLResolver::class, 'load']),

    CacheInterface::class => DI\factory([\Library\Cache\Factory::class, 'make']),
    'cache' => DI\get(CacheInterface::class),

    Config::class => DI\factory([Config::class, 'build']),
    'config' => DI\get(Config::class),
];
