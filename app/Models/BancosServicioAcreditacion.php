<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancosServicioAcreditacion extends Model
{
    use HasFactory;
    protected $table = "bancos_servicio_acreditacions";

    protected $fillable = [
        'servicios_envases_ruta_id',
        'folio',
        'status_acreditacion'
    ];

    public function envase(){
        return $this->belongsTo(ServicioRutaEnvases::class, 'servicios_envases_ruta_id');
    }
}
