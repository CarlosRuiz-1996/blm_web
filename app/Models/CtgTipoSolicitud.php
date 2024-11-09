<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgTipoSolicitud extends Model
{
    use HasFactory;
    protected $table = 'ctg_tipo_solicitud';
    protected $fillable = [
        'name','status_ctg_tipo_solicitud'
    ];
}
