<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetallesCompraEfectivo extends Model
{
    use HasFactory;
    protected $table = "detalles_compra_efectivos";
    protected $fillable = [
        'compra_efectivo_id',
        'monto',
        'consignatario_id',
        'status_detalles_compra_efectivos'
    ];

    

    public function consignatario()
    {
        return $this->belongsTo(CtgConsignatario::class, 'consignatario_id');
    }
    public function compra_efectivo()
    {
        return $this->belongsTo(CompraEfectivo::class, 'compra_efectivo_id');
    }

    public function envase(){
        return $this->hasOne(CompraEfectivoEnvases::class, 'detalles_compra_efectivo_id');
    }
}
