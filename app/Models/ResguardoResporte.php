<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResguardoResporte extends Model
{
    use HasFactory;

    protected $table = 'resguardo_resporte'; // especifica el nombre de la tabla

    protected $fillable = [
        'servicio_id',
        'resguardo_actual',
        'cantidad',
        'tipo_servicio',
        'status_reporte_resguardo',
    ];

    // Definir la relación con el modelo Servicio
    public function servicio()
    {
        return $this->belongsTo(Servicios::class);
    }
}
