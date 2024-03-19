<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class validacionjuridicobene extends Model
{
    use HasFactory;

    protected $table = 'juridico_doc_validacion_beneficiario';
    protected $fillable = [
        'juridico_id','expediente_documentos_benf_id','cumple','nota','status_validacion_doc_juridico_beneficiario'


    ];
}


