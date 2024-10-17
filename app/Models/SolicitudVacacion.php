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

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
    public function motivo()
    {
        return $this->belongsTo(CtgMotivoVacaciones::class, 'ctg_motivo_vacaciones_id');
    }
}
