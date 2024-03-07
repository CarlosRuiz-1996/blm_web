<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servicios extends Model
{
    use HasFactory;
    protected $table = 'servicios';
    protected $fillable = [
        'precio_unitario','cantidad','subtotal','ctg_precio_servicio_id','ctg_servicios_id','servicio_especial','status_servicio'
    ];

    
}