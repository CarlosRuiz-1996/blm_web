<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancosServicios extends Model
{
    use HasFactory;
    protected $table= "bancos_servicios";
    protected $fillable = [
        'servicio_id',
        'monto',
        'papeleta',
        'fecha_entrega',
        'tipo_servicio',
        'status_bancos_servicios'
    ];
    

    public function servicio (){
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }
}
