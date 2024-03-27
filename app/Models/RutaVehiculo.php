<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaVehiculo extends Model
{
    use HasFactory;

    protected $table = "ruta_vehiculos";
    protected $fillable = ['ruta_id', 'ctg_vehiculo_id'];


    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function vehiculo(){
        return $this->belongsTo(CtgVehiculos::class, 'ctg_vehiculo_id');
    }
}
