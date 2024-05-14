<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaEmpleadoReporte extends Model
{
    use HasFactory;
    protected $table = "ruta_empleado_reportes";

    protected $fillable = ['empleado_id', 'ruta_id','status_ruta_empleado_reportes'];

}
