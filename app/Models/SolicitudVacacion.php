<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudVacacion extends Model
{
    protected $table = 'solicitudes_vacaciones';
    protected $fillable = [
        'empleado_id',
        'fecha_inicio',
        'fecha_fin',
        'status_vacaciones',
        'ctg_motivo_vacaciones_id'
    ];
}
