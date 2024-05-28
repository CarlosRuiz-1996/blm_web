<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Empleado extends Model
{
    use HasFactory;
 use Notifiable;
    protected $table = 'empleados';
    protected $fillable = [
       'user_id',
       'direccion',
       'ctg_cp_id',
       'sexo',
       'phone',
       'ctg_area_id',
       'status_empleado',
       'fecha_nacimiento',
       'cve_empleado',
    ];
    
    //relaciones de usuario/cliente/revisor area
    public function area()
    {
        return $this->belongsTo(Ctg_Area::class, 'ctg_area_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_empleados', 'empleado_id', 'ruta_id');
    }
}
