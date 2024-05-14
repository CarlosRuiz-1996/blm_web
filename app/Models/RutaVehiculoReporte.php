<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaVehiculoReporte extends Model
{
    use HasFactory;
    protected $table = "ruta_vehiculo_reportes";
    protected $fillable = ['ruta_id', 'ctg_vehiculo_id','status_ruta_vehiculo_reportes'];


}
