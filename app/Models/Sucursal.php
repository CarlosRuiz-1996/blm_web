<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    
    use HasFactory;
    protected $table = 'sucursal';
    protected $fillable = [
       'sucursal',
       'correo',
       'phone',
       'contacto',
       'cargo',
       'fecha_inicio_servicio',
       'fecha_evaluacion',
       'cliente_id',
       'ctg_cp_id',
       'referencias',
       'direccion',
       'longitud',
       'latitud',
       'rpt_factibilidad_status',
       'status_sucursal'

    ];

    public function sucursal_servicio()
    {
        return $this->hasMany(SucursalServicio::class, 'sucursal_id');
    }

    public function cp()
    {
        return $this->belongsTo(Ctg_Cp::class, 'ctg_cp_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');

    }

    
}
