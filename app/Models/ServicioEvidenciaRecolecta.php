<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioEvidenciaRecolecta extends Model
{
    protected $table = 'servicios_evidencias_recolecta';

    protected $fillable = [
        'ruta_servicios_id', 'status_evidencia_recolecta'
    ];

    public function rutaServicio()
    {
        return $this->belongsTo(RutaServicio::class);
    }
}
