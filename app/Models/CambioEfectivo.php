<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CambioEfectivo extends Model
{
    use HasFactory;
    protected $table="cambio_efectivos";
    protected $fillable=['monto','empleado_boveda_id','from_change','status_cambio_efectivo'];

    public function denominacions(){
        return $this->hasMany(CambioEfectivoDenominaciones::class, 'cambio_efectivo_id');
    }

    public function empleado(){
        return $this->belongsTo(Empleado::class, 'empleado_boveda_id');
    }
}
