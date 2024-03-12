<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cumplimiento_aceptado extends Model
{
    use HasFactory;
    protected $table = 'cumplimiento_aceptado';
    protected $fillable = [
        'cumplimiento_id','ctg_aceptado_id','status_cumplimiento_aceptado',
    ];
    

    
}