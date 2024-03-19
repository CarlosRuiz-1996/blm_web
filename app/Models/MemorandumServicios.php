<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumServicios extends Model
{
    use HasFactory;
    protected $table = 'memorandum_servicios';
    protected $fillable = [
        'sucursal_servicio_id',
        'memoranda_id',
        'ctg_dia_servicio_id',
        'ctg_dia_entrega_id',
        'ctg_horario_servicio_id',
        'ctg_horario_entrega_id',
        'ctg_consignatario_id',
        'status_sucursal_entregas',
    ];


    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class, 'memoranda_id');
    }

    public function sucursal_servicio()
    {
        return $this->belongsTo(SucursalServicio::class, 'sucursal_servicio_id');
    }
    
    public function dia_servicio()
    {
        return $this->belongsTo(CtgDiaServicio::class, 'ctg_dia_servicio_id');
    }
    public function dia_entrega()
    {
        return $this->belongsTo(CtgDiaEntrega::class, 'ctg_dia_entrega_id');
    }
    public function hora_servicio()
    {
        return $this->belongsTo(Ctg_Horario_Servicio::class, 'ctg_horario_servicio_id');
    }
    public function hora_entrega()
    {
        return $this->belongsTo(CtgHorarioEntrega::class, 'ctg_horario_entrega_id');
    }
    public function consignatario()
    {
        return $this->belongsTo(CtgConsignatario::class, 'ctg_consignatario_id');
    }
   
}
