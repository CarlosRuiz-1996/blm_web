<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioPuerta extends Model
{
    use HasFactory;
    protected $table = "servicio_puertas";
    protected $fillable = [
        'name_entrega',
        'ruta_servicio_id',
        'recolecta',
        'entrega',
        'status_puerta_servicio'
    ];


    public function rutaServicio(){
        return $this->belongsTo(RutaServicio::class, 'ruta_servicio_id');
    }
}
