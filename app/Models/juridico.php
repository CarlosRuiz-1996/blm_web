<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class juridico extends Model
{
    use HasFactory;
    protected $table = 'juridico';
    protected $fillable = [
        'expediente_digital_id','dictamen','fecha_dictamen','status_juridico'
    ];
    

    
}