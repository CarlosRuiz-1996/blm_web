<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioComision extends Model
{
    use HasFactory;
    protected $table = "servicio_comisions";
    protected $fillable = ['papeleta','monto','ruta_servicio_id','status_servicio_comisions'];
}
