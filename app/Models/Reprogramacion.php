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
        'ruta_id_new',
        'ruta_id_old'
    ];

    public function ruta_servicio(){
        return $this->belongsTo(RutaServicio::class, 'ruta_servicio_id');
    }

    public function area(){
        return $this->belongsTo(Ctg_Area::class, 'area_id');
    }
    public function rutaNew() {
        return $this->belongsTo(Ruta::class, 'ruta_id_new');
    }

    // Relación con la ruta antigua
    public function rutaOld() {
        return $this->belongsTo(Ruta::class, 'ruta_id_old');
    }
}
