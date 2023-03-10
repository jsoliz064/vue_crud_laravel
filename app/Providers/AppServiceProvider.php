<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    private $cache;

    public function __construct()
    {
    }

    public function register()
    {

    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('cache', function ($key = null, $ttl = null) {

            $tableName = $key ?: md5($this->toSql() . serialize($this->getBindings()));//key en cache
            $Max = 100000; //Cantidad Max de tuplas por Cache
            $tamanoPartition = 50000; //Cantidad de Tuplas por particion
            $timeCache = $ttl?:60 * 60; //Tiempo en cache en seg

            $query_count = function () use ($tableName, $timeCache) {
                return Cache::remember($tableName . "_total", $timeCache, function () {
                    return $this->count();
                });
            };

            if ($query_count() < $Max) {
                return Cache::remember($key, $ttl, function () {
                    return $this->get();
                });
            }
            $query_num_partition = function () use ($tamanoPartition, $query_count) {
                return ceil($query_count() / $tamanoPartition);
            };

            $query_keys_partitions = function () use ($tableName, $timeCache, $tamanoPartition, $query_num_partition) {
                return Cache::remember($tableName . "_keys", $timeCache, function () use ($tableName, $tamanoPartition, $query_num_partition) {
                    $numPartitionCache = $query_num_partition();
                    $keys = [];
                    $begin = 0;
                    $end = 0;
                    for ($i = 1; $i <= $numPartitionCache; $i++) {
                        $end += $tamanoPartition;
                        $keys[] = $tableName . "_" . $begin . "_" . $end;
                        $begin = $end;
                    }
                    return $keys;
                });
            };

            function key_decrypt($key)
            {
                $parts = explode('_', $key);
                return array('inicio' => $parts[1], 'fin' => $parts[2]);
            }

            $create_partition = function ($key) use ($timeCache, $tamanoPartition) {
                $partition = Cache::remember($key, $timeCache, function () use ($key, $tamanoPartition) {
                    $key = key_decrypt($key);
                    return $this->offset($key['inicio'])->limit($tamanoPartition)->orderBy('id')->get();
                });
                return $partition;
            };

            $query_load_Cache = function () use ($query_keys_partitions, $create_partition) {
                $keysPartitions = $query_keys_partitions();
                foreach ($keysPartitions as $key) {
                    $partitionCache = $create_partition($key);
                }
                return $keysPartitions;
            };

            return $query_load_Cache();
        });
    }
}
