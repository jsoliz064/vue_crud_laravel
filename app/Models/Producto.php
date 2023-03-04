<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheableTrait;
class Producto extends Model
{
    use HasFactory;
    // use CacheableTrait;
    protected $table = "productos";
    protected $guarded = ['id'];

}
