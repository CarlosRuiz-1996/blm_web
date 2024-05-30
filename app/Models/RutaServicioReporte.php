<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaServicioReporte extends Model
{
    use HasFactory;
     // Nombre de la tabla en la base de datos
     protected $table = 'ruta_servicio_reportes';

     // Atributos asignables en masa
     protected $fillable = [
         'servicio_id', 'ruta_id', 'monto', 'folio', 'envases', 'tipo_servicio', 'status_ruta_servicio_reportes','motivocancelacion','area'
     ];
     public function servicio(){
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }


    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }
}
