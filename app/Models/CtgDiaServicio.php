<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgDiaServicio extends Model
{
    use HasFactory;
    protected $table = 'ctg_dia_servicio';
    protected $fillable = [
        'name','status_ctg_dia_servicio'
    ];
}
