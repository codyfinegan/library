<?php

namespace Library\Cache;

use Psr\SimpleCache\CacheInterface;
use Shieldon\SimpleCache\Cache as BaseCache;

/**
 * Wrap to allow PSR to be discoverable by DI
 */
class Cache extends BaseCache implements CacheInterface {

}
