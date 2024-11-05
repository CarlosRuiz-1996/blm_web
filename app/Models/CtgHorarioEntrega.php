<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgHorarioEntrega extends Model
{
    use HasFactory;
    protected $table = 'ctg_horario_entrega';
    protected $fillable = [
        'name','status_ctg_horario_entrega'
    ];
}
