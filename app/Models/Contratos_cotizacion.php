<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratos_cotizacion extends Model
{
    use HasFactory;
    protected $table = 'contratos_cotizacion';

    protected $fillable = [

    'status_editado','cliente_id','cotizacion_id', 'status_contrato', 'ctg_contratos_id', 'apoderado', 'escritura', 'licenciado', 'foliomercantil', 'fecharegistro', 'lugarregistro', 'notario', 'numnotario', 'fechapodernotarial', 'ciudadnotaria'

    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }
    public function ctg_contratos()
    {
        return $this->belongsTo(Ctg_Contratos::class, 'ctg_contratos_id');
    }
    
}
