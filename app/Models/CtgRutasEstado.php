<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgRutasEstado extends Model
{
    use HasFactory;
    protected $table = 'ctg_rutas_estados';
    protected $fillable = [
       'name',
       'status_ctg_rutas_estados',
    ];
}
