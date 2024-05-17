<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionHerramientaEmpleado extends Model
{
    use HasFactory;
    protected $table = 'asignaciones_herramientas_empleados';
    protected $fillable = [
        'empleado_id',
        'herramienta_id',
        'status_asignacion_herramienta',
        'fecha_entrega',
        'fecha_cambio',
        'fecha_devolucion',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function herramienta()
    {
        return $this->belongsTo(HerramientaEmpleado::class);
    }
}
