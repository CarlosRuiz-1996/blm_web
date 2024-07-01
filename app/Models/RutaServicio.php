<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaServicio extends Model
{
    use HasFactory;
    protected $table = "ruta_servicios";
    protected $fillable = ['servicio_id', 'ruta_id', 'monto', 'folio', 'envases', 'tipo_servicio', 'status_ruta_servicios','envase_cargado'];


    public function servicio(){
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }


    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function envases_servicios(){
        return $this->hasMany(ServicioRutaEnvases::class, 'ruta_servicios_id');
    }


    // public function envase_servicio(){
    //     return $this->hasOne(ServicioRutaEnvases::class, 'ruta_servicios_id');
    // }
}
