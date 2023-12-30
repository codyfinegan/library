<?php

use Library\Kernel;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Middleware\ContentLengthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

Kernel::make(__DIR__ . '/../bootstrap.php')
    ->errors()
    ->env(__DIR__ . '/..')
    ->middleware([
        BodyParsingMiddleware::class,
        \Library\Http\Middleware\JsonResponse::class,
        ContentLengthMiddleware::class,
    ])
    ->registerRoutes(__DIR__ . '/../routes')
    ->boot()
;
