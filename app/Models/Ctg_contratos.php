<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctg_Contratos extends Model
{
    use HasFactory;
    protected $table = 'ctg_contratos';
    protected $fillable = [
        'id', 'nombre', 'path', 'status_contrato',
    ];
}
