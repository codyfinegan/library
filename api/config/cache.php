<?php

return [

    'default' => env('CACHE_DRIVER', 'file'),

    'drivers' => [
        'redis' => [

        ],
        'file' => [
            'storage' => 'cache',
        ],
    ]

];