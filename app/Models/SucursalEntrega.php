<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalEntrega extends Model
{
    use HasFactory;
    protected $table = 'sucursal_entregas';
    protected $fillable = [
        'sucursal_id',
        'memoranda_id',
        'ctg_dia_servicio_id',
        'ctg_dia_entrega_id',
        'ctg_horario_servicio_id',
        'ctg_horario_entrega_id',
        'ctg_consignatario_id',
        'status_sucursal_entregas',
    ];
}
