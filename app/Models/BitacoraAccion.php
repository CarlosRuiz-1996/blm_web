<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraAccion extends Model
{
    use HasFactory;
    protected $table = 'bitacora_accions';
    protected $fillable = [
        'name','status'
    ];
}
