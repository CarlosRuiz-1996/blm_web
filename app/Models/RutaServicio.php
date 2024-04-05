<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaServicio extends Model
{
    use HasFactory;
    protected $table = "ruta_servicios";
    protected $fillable = ['servicio_id', 'ruta_id', 'monto', 'folio', 'envases', 'tipo_servicio', 'status_ruta_servicios'];


    public function servicio (){
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }
}
