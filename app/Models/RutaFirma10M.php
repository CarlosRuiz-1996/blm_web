<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaFirma10M extends Model
{
    use HasFactory;
    protected $table = "ruta_firma10_m_s";
    //status 0-espera/1-aceptado/2-terminado/3-rechazado
    protected $fillable = [
        'ruta_id', 'empleado_id_boveda', 'confirm_boveda', 'empleado_id_operaciones', 'confirm_operaciones',
        'empleado_id_direccion', 'confirm_direccion', 'status_ruta_firma10_m_s'
    ];
}
