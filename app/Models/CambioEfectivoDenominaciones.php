<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CambioEfectivoDenominaciones extends Model
{
    use HasFactory;
    protected $table="cambio_efectivo_denominaciones";
    protected $fillable=['monto','ctg_denominacion_id','cambio_efectivo_id','status_cambio_efectivo_denominaciones'];


    public function cambio_efectivo(){
        return $this->belongsTo(CambioEfectivo::class, 'cambio_efectivo_id');
    }

    public function tipo_denominacion(){
        return $this->belongsTo(ctgDenominacion::class, 'ctg_denominacion_id');
    }
}
