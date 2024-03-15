<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumValidacion extends Model
{
    use HasFactory;

    protected $table = 'memoranda_validacion';
    protected $fillable = [
        'revisor_areas_id',
        'memoranda_id',
        'status_validacion_memoranda',


    ];
}
