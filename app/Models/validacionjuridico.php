<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class validacionjuridico extends Model
{
    use HasFactory;

    protected $table = 'juridico_doc_validacion';
    protected $fillable = [
        'juridico_id','expediente_documentos_id','cumple','nota','status_validacion_doc_juridico'


    ];
}


