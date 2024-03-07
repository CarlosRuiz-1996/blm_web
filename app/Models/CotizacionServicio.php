<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionServicio extends Model
{
    use HasFactory;
    protected $table = 'cotizacion_servicio';

    public function servicios()
    {
        return $this->belongsTo(Servicios::class, 'id');
    }

    public function cotizaciones()
    {
        return $this->belongsTo(Cotizacion::class, 'id');
    }
}
