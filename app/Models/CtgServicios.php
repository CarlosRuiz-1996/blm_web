<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgServicios extends Model
{
    use HasFactory;
    protected $table = 'ctg_servicios';

    protected $fillable = [
        'folio', 'tipo', 'descripcion', 'unidad','status_servicio'
        // Agrega aquí cualquier otro campo que desees asignar masivamente
    ];
}
