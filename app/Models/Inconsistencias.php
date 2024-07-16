<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inconsistencias extends Model
{
    use HasFactory;

    protected $table = "inconsistencias";
    protected $fillable = [
        'cliente_id',
        'ruta_servicio_reportes_id',
        'fecha_comprobante',
        'folio',
        'importe_indicado',
        'importe_comprobado',
        'diferencia',
        'tipo',
        'observacion',
        'status_incosistencia',
    ];
}
