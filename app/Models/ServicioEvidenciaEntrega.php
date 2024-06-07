<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioEvidenciaEntrega extends Model
{
    protected $table = 'servicios_evidencias_entrega';

    protected $fillable = [
        'servicio_envases_ruta_id', 'status_evidencia_entrega'
    ];

    public function envases()
    {
        return $this->belongsTo(ServicioRutaEnvases::class);
    }
}
