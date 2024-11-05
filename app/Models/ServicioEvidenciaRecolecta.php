<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioEvidenciaRecolecta extends Model
{
    protected $table = 'servicios_evidencias_recolecta';

    protected $fillable = [
        'servicio_envases_ruta_id', 'status_evidencia_recolecta', 'violate'
    ];

    // public function envases()
    // {
    //     return $this->belongsTo(ServicioRutaEnvases::class);
    // }
    // public function envases()
    // {
    //     return $this->belongsTo(ServicioRutaEnvases::class);
    // }
}
