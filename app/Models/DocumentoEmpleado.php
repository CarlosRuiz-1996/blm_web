<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoEmpleado extends Model
{
    use HasFactory;
    protected $table = 'documentos_empleados';
    protected $fillable = [
        'empleado_id',
        'ctg_documentos_expediente_empleados_id',
        'nombre_archivo',
        'url_archivo',
        'status_documento_empleado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function ctgDocumento()
    {
        return $this->belongsTo(CtgDocumentoExpedienteEmpleado::class, 'ctg_documentos_expediente_empleados_id');
    }
}
