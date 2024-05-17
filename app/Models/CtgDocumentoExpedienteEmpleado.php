<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgDocumentoExpedienteEmpleado extends Model
{
    use HasFactory;
    protected $table = 'ctg_documentos_expediente_empleados';
    protected $fillable = [
        'nombre',
        'status_ctg_documento',
    ];
}
