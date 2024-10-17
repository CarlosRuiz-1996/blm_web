<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Empleado extends Model
{
    use HasFactory;
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
        'talla_camisa',
        'talla_pantalon',
        'talla_zapatos',
        'nombre_emergencia1',
        'telefono_emergencia1',
        'parentesco_emergencia1',
        'direccion_emergencia1',
        'nombre_emergencia2',
        'telefono_emergencia2',
        'parentesco_emergencia2',
        'direccion_emergencia2',
        'alergias',
        'tipo_sangre',
        'umf',
        'hospital'
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

    public function armado (){
        return $this->hasOne(EmpleadoArmado::class, 'empleado_id');
    }
    public function solicitudesVacaciones()
    {
        return $this->hasMany(SolicitudVacacion::class, 'empleado_id');
    }
}
