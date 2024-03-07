<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumSucursal extends Model
{
    use HasFactory;
    protected $table = 'memorandum_sucursal';
    protected $fillable = [
        'sucursal_id',
        'memoranda_id',
        'status_memorandum_sucursal',
    ];
}
