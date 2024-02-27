<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
      
        'puesto',
        'direccion',
        'ctg_cp_id',
        'razon_social',
        'rfc_cliente',
        'phone',
        'resguardo',
        'status_cliente',
        'ctg_tipo_cliente_id',
    ];
}
