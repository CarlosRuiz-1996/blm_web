<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cotizacion extends Model
{
    use HasFactory;
    protected $table = 'cotizacion';
    protected $fillable = [
        'total','vigencia','ctg_tipo_pago_id','cliente_id','status_cotizacion'
    ];
    

    
}