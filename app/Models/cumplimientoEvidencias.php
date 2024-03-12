<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cumplimientoEvidencias extends Model
{
    use HasFactory;
    protected $table = 'cumplimiento_evidencias';
    protected $fillable = [
        'id','cumplimiento_id','document_name','cumple','nota','status_cumplimiento_evidencias'
    ];
    

    
}
