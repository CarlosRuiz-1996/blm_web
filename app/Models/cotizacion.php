<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;
    protected $table = 'cotizacion';
    protected $fillable = [
        'total','vigencia','ctg_tipo_pago_id','cliente_id','status_cotizacion'
    ];
    public function cotizacion_servicio()
    {
        return $this->hasMany(CotizacionServicio::class, 'cotizacion_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function anexo()
    {
        return $this->hasOne(Anexo1::class, 'cotizacion_id');
    }

}
