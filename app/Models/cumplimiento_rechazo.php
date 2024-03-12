<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cumplimiento_rechazo extends Model
{
    use HasFactory;
    protected $table = 'cumplimiento_rechazo';
    protected $fillable = [
        'id','cumplimiento_id','ctg_rechazo_id','status_cumplimiento_rechazo',
    ];
    

    
}