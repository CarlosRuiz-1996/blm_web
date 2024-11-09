<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgTipoServicio extends Model
{
    use HasFactory;
    protected $table = 'ctg_tipo_servicio';
    protected $fillable = [
        'name','status_ctg_tipo_servicio'
    ];
}
