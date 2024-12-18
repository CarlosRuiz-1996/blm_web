<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtgVehiculos extends Model
{
    use HasFactory;
    protected $table = 'ctg_vehiculos';
    protected $fillable = [
        'descripcion', 'serie', 'anio', 'ctg_vehiculo_modelo_id', 'placas', 'status_ctg_vehiculos'
    ];


    public function modelo(){
        return $this->belongsTo(CtgVehiculosModelo::class,'ctg_vehiculo_modelo_id');
    }

    
    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_vehiculos', 'ctg_vehiculo_id', 'ruta_id');
    }
}
