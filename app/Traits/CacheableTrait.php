<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait CacheableTrait
{
   
    public function newQuery($excludeDeleted = true)
    {
        // Almacenar en caché todas las consultas durante 60 segundos
        $cacheKey = 'productos_' . implode('_', func_get_args());
        return cache()->remember($cacheKey, 60, function () use ($excludeDeleted) {
            return parent::newQuery($excludeDeleted);
        });
    }
    
}
