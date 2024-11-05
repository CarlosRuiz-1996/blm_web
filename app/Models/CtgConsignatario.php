<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgConsignatario extends Model
{
    use HasFactory;
    
    protected $table = 'ctg_consignatario';
    protected $fillable = [
        'name','status_ctg_consignatario'
    ];
}
