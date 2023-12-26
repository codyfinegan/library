<?php

use Library\Http\Controller\Index;
use Slim\App;

return function (App $app) {
    $app->map(['GET', 'POST'], '/', Index::class);
};

