<?php

use Library\Http\Message\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    ResponseFactoryInterface::class => Di\create(ResponseFactory::class)
];
