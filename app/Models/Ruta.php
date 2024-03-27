<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;
    protected $table = 'rutas';
    protected $fillable = [
        'hora_inicio',
        'hora_fin',
        'ctg_rutas_id',
        'ctg_ruta_dia_id',
        'ctg_rutas_riesgo_id',
        'ctg_rutas_estado_id',
        'status_ruta'
    ];



    public function nombre()
    {
        return $this->belongsTo(CtgRutas::class, 'ctg_rutas_id');
    }
    public function dia()
    {
        return $this->belongsTo(CtgRutaDias::class, 'ctg_ruta_dia_id');
    }
    public function riesgo()
    {
        return $this->belongsTo(CtgRutasRiesgo::class, 'ctg_rutas_riesgo_id');
    }
    public function estado()
    {
        return $this->belongsTo(CtgRutasEstado::class, 'ctg_rutas_estado_id');
    }
}
