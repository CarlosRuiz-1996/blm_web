<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $fillable = [
       'user_id',
       'direccion',
       'ctg_cp_id',
       'sexo',
       'phone',
       'ctg_area_id',
       'status_empleado',
       'fecha_nacimiento'
    ];
    
    //relaciones de usuario/cliente/revisor area
    public function area()
    {
        return $this->belongsTo(Ctg_Area::class, 'ctg_area_id');
    }

}
