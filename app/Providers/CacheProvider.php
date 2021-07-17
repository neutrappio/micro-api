<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;
use Exception;

use Phalcon\Cache;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Storage\Serializer\Json as JsonSerializer;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;

class CacheProvider extends Provider
{
    const SERVICE_NAME = "cache";

    public function init(IApplication $application) : Closure
    {
        return function () {
            $config = $this->getConfig();
            $cacheAdapter = $config->cache->adapter;

            $jsonSerializer = new JsonSerializer();

            if (!$config->cache->options[$cacheAdapter]) {
                throw new Exception("Cache Adapter $cacheAdapter Options null");
            }

            $cacheOptions = [
                'lifetime'          => 7200, // default 2h
                'serializer'        => $jsonSerializer // method of parse/save cache
            ];

            $cacheOptions += $config->cache->options[$cacheAdapter]->toArray() ?? [] ;

            $serializerFactory = new SerializerFactory();
        
            $cacheAdapter = "\Phalcon\Cache\Adapter\\{$cacheAdapter}";
            $adapter = new $cacheAdapter($serializerFactory, $cacheOptions);

            return new Cache($adapter);
        };
    }
}
