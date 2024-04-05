<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;
    protected $table = 'servicios';
    protected $fillable = [
        'precio_unitario','cantidad','subtotal','ctg_precio_servicio_id','ctg_servicios_id',
        'servicio_especial','status_servicio','cliente_id'
    ];

    public function ctg_servicio()
    {
        return $this->belongsTo(CtgServicios::class, 'ctg_servicios_id');
    }

    public function cotizacion_servicio()
    {
        return $this->hasMany(CotizacionServicio::class, 'servicio_id');
    }

    public function sucursal_servicio()
    {
        return $this->hasMany(SucursalServicio::class, 'servicio_id');
    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
