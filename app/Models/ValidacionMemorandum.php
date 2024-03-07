<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidacionMemorandum extends Model
{
    use HasFactory;

    protected $table = 'validacion_memoranda';
    protected $fillable = [
        'revisor_areas_id',
        'memoranda_id',
        'status_validacion_memoranda',


    ];
}
