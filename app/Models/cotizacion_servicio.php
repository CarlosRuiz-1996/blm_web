<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cotizacion_servicio extends Model
{
    use HasFactory;
    protected $table = 'cotizacion_servicio';
    protected $fillable = [
        'cotizacion_id','servicio_id','status_cotizacion_servicio',
    ];
    

    
}