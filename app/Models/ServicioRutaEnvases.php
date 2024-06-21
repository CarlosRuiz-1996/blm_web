<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioRutaEnvases extends Model
{
    protected $table = "servicios_envases_rutas";
    protected $fillable = [
        'ruta_servicios_id',
        'tipo_servicio',
        'cantidad',
        'folio',
        'status_envases',
        'sello_seguridad'
    ];

    // Relación con la tabla de rutas de servicios
    public function rutaServicios()
    {
        return $this->belongsTo(RutaServicio::class, 'ruta_servicios_id');
    }

    public function evidencia_recolecta(){
        return $this->hasOne(ServicioEvidenciaRecolecta::class, 'servicio_envases_ruta_id');
    }


    public function evidencia_entrega(){
        return $this->hasOne(ServicioEvidenciaEntrega::class, 'servicio_envases_ruta_id');
    }
}
