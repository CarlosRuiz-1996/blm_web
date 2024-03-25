<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgVehiculosModelo extends Model
{
    use HasFactory;
    protected $table = 'ctg_vehiculos_modelos';
    protected $fillable = [
        'name','status_ctg_vehiculos_modelos','ctg_vehiculo_marca_id'
    ];

    
    public function marca(){
        return $this->belongsTo(CtgVehiculosMarca::class, 'ctg_vehiculo_marca_id');
    }
}
