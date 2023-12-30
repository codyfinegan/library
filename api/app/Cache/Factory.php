<?php

namespace Library\Cache;

use DI\Container;
use Library\Config;
use Psr\SimpleCache\CacheInterface;

class Factory
{
    public static function make(Config $config): CacheInterface
    {
        $driver = $config->get('cache.default');
        $drivers = $config->get('cache.drivers');

        $settings = $drivers[$driver] ?? null;
        if (empty($driver)) {
            throw new \Exception('Unable to initiate cache "' . $driver . '"');
        }

        // Handle situations where it's a specific type
        if ($driver === 'file') {
            $settings['storage'] = storage($settings['storage']);
        }

        return new Cache($driver, $settings);
    }
}