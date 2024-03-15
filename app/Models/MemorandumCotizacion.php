<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumCotizacion extends Model
{
    use HasFactory;
    protected $table = 'memorandum_cotizacion';
    protected $fillable = [
        'memoranda_id',
        'cotizacion_id',
        'status_memorandum_cotizacion',
    ];
}
