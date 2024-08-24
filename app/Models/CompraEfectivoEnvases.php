<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraEfectivoEnvases extends Model
{
    use HasFactory;
    protected $table = "compra_efectivo_envases";
    protected $fillable = [
        'detalles_compra_efectivo_id',
        'monto',
        'papeleta',
        'sello_seguridad',
        'evidencia',
        'status_compra_efectivo_envases'
    ];
}
