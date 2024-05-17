<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HerramientaEmpleado extends Model
{
    use HasFactory;
    protected $table = 'herramientas_empleados';
    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad_disponible',
        'status_herramienta_empleado',
    ];
}
