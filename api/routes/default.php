<?php

use Slim\Routing\RouteCollectorProxy;

return function (RouteCollectorProxy $group) {
    $group->map(['GET'], '/', \Library\Http\Controller\Index::class);
    $group->map(['GET', 'POST'], '/graphql', \Library\Http\Controller\Graphql::class);
};

