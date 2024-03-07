<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expediente_documento_beneficiario extends Model
{
    use HasFactory;
    protected $table = 'expediente_documentos_benf';
    protected $fillable = [
        'expediente_digital_id','ctg_documentos_benf_id','document_name','status_expediente_doc_benf'
    ];
}
