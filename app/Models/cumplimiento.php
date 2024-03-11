<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cumplimiento extends Model
{
    use HasFactory;
    protected $table = 'cumplimiento';
    protected $fillable = [
        'expediente_digital_id','dictamen','fecha_dictamen','status_cumplimiento'
    ];
    

    
}