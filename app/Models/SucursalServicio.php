<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalServicio extends Model
{
    use HasFactory;
    
    protected $table = 'sucursal_servicio';
    protected $fillable = ['servicio_id', 'sucursal_id', 'status_sucursal_servicio', 'anexo1_id'];

    
    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function servicio_memo()
    {
        return $this->hasOne(MemorandumServicios::class, 'sucursal_servicio_id');
    }

    public function anexo()
    {
        return $this->belongsTo(Anexo1::class, 'anexo1_id');
    }
  
}
