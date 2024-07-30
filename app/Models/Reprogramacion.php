<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reprogramacion extends Model
{
    use HasFactory;
    protected $table="reprogramacions";
    protected $fillable=[
        'motivo',
        'evidencia',
        'ruta_servicio_id',
        'status_reprogramacions',
        'area_id',
        'ruta_servicio_id_new'
    ];

    public function ruta_servicio(){
        return $this->belongsTo(RutaServicio::class, 'ruta_servicio_id');
    }

    public function area(){
        return $this->belongsTo(Ctg_Area::class, 'area_id');
    }
}
