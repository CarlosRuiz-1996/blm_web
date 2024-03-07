<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expediente_digital extends Model
{
    use HasFactory;
    protected $table = 'expediente_digital';
    protected $fillable = [
        'cumplimiento','juridico','fecha_solicitud','cliente_id','status_expediente_digital'
    ];
    

    
}