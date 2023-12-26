<?php

return function (\Slim\App $app) {
    $app->get('/', \Library\Http\Controller\Index::class);
};

