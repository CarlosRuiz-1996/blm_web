<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expediente_documentos extends Model
{
    use HasFactory;
    protected $table = 'expediente_documentos';
    protected $fillable = [
        'expediente_digital_id','ctg_documentos_id','document_name','status_expediente_doc'
    ];
}
