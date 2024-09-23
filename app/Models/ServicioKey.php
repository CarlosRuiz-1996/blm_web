<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioKey extends Model
{
    use HasFactory;
    protected $table = "servicio_keys";
    protected $fillable = ['ruta_servicio_id', 'key','status_servicio_keys'];
}
