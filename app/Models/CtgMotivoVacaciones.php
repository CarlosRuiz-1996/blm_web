<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtgMotivoVacaciones extends Model
{
    protected $table = 'ctg_motivo_vacaciones';
    protected $fillable = [
        'motivo',
        'status_vacaciones'
    ];
    // Si no deseas que Laravel maneje las columnas de timestamps, puedes establecer
    // public $timestamps = false;
}
