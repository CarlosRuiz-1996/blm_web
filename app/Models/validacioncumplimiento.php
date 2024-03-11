<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class validacioncumplimiento extends Model
{
    use HasFactory;

    protected $table = 'cumplimiento_doc_validacion';
    protected $fillable = [
        'cumplimiento_id','expediente_documentos_id','cumple','nota','status_validacion_doc_cumplimiento'


    ];
}


