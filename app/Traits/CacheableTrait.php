<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheableTrait
{
    protected $tamanoPartition = 10000; //cantidad de tuplas por particion
    protected $timeCache = 60 * 60; //seg

    public function Cache()
    {
        $keysPartitions = $this->keys_partitions();
        foreach ($keysPartitions as $key) {
            $partitionCache = $this->create_partition($key);
        }
        return "termino";
    }
    public function WhereCache($querys)
    {
        $paquetes = $this->keys_partitions();
        $result = collect();
        foreach ($paquetes as $paquete) {
            $datas = Cache::get($paquete);
            $data = $this->Wheres($querys,$datas);
            if (sizeof($data)>0){
                $result = $result->concat($data);
            }
        }
        return $result;
    }
    public function Wheres($querys,$collection){
        foreach($querys as $query){
            $collection = $collection->where($query[0],$query[1],$query[2]);
        }
        return $collection;
    }
    public function keys_partitions()
    {
        $tableName = $this->getTable();
        return Cache::remember($tableName . "_keys", $this->timeCache, function () use ($tableName) {
            $numPartitionCache = $this->model_num_partition();
            $keys_partitions = [];
            $begin = 0;
            $end = 0;
            for ($i = 1; $i <= $numPartitionCache; $i++) {
                $end += $this->tamanoPartition;
                $keys_partitions[] = $tableName . "_" . $begin . "_" . $end;
                $begin = $end;
            }
            return $keys_partitions;
        });
    }
    public function model_num_partition()
    {
        return ceil($this->model_total() / $this->tamanoPartition);
    }
    public function model_total()
    {
        $table = $this->getTable();
        return Cache::remember($table . "_total", $this->timeCache, function () {
            return $this::count();
        });
    }
    public function create_partition($key)
    {
        $partition = Cache::remember($key, $this->timeCache, function () use ($key) {
            $key = $this->key_decrypt($key);
            return $this::offset($key['inicio'])->limit($this->tamanoPartition)->orderBy('id')->get();
        });
        return $partition;
    }
    public function key_decrypt($key)
    {
        $parts = explode('_', $key);
        return array('inicio' => $parts[1], 'fin' => $parts[2]);
    }
}
