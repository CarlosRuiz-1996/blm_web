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
    ];

    // Relación con la tabla de rutas de servicios
    public function rutaServicios()
    {
        return $this->belongsTo(RutaServicio::class, 'ruta_servicios_id');
    }
}
