<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheableTrait;

class Venta extends Model
{
    use HasFactory;
    use CacheableTrait;
    protected $table = "ventas";
    protected $guarded = ['id'];
}
