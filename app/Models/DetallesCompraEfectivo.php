<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesCompraEfectivo extends Model
{
    use HasFactory;
    protected $table = "detalles_compra_efectivos";
    protected $fillable = [
        'compra_efectivo_id',
        'monto',
        // 'cliente_id',
        'consignatario_id',
        'status_detalles_compra_efectivos'
    ];

    // public function cliente(){
    //     return $this->belongsTo(Cliente::class, 'cliente_id');
    // }

    public function consignatario()
    {
        return $this->belongsTo(CtgConsignatario::class, 'consignatario_id');
    }
    public function compra_efectivo()
    {
        return $this->belongsTo(CompraEfectivo::class, 'compra_efectivo_id');
    }
}
