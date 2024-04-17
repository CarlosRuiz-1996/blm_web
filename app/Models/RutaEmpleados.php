<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaEmpleados extends Model
{
    use HasFactory;

    protected $table = "ruta_empleados";

    protected $fillable = ['empleado_id', 'ruta_id','status_ruta_empleados'];

    public function empleado(){
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }


    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_vehiculos', 'ctg_vehiculo_id', 'ruta_id');
    }
}
