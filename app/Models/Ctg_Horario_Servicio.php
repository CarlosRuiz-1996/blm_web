<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctg_Horario_Servicio extends Model
{
    use HasFactory;
    protected $table = 'ctg_horario_servicio';
    protected $fillable = [
        'name','status_ctg_horario_servicio'
    ];
}
