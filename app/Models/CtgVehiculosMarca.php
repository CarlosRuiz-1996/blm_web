<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgVehiculosMarca extends Model
{
    use HasFactory;
    protected $table = 'ctg_vehiculos_marcas';
    protected $fillable = [
        'name','status_ctg_vehiculos_marcas'
    ];
}
