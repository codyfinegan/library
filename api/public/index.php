<?php

use Library\Kernel;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Middleware\ContentLengthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

Kernel::make(__DIR__ . '/../bootstrap.php')
    ->configure()
    ->middleware([
        BodyParsingMiddleware::class,
        \Library\Middleware\JsonResponse::class,
        ContentLengthMiddleware::class,
    ])
    ->registerRoutes(__DIR__ . '/../config/routes.php')
    ->app()
    ->run();
//
;