<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraEfectivo extends Model
{
    use HasFactory;
    protected $table = "compra_efectivos";
    protected $fillable = [
        'consignatario_id',
        'total',
        'fecha_compra',
        'status_compra_efectivos'
    ];

    public function consignatario(){
        return $this->belongsTo(CtgConsignatario::class,'consignatario_id');
    }

    public function detalles(){
        return $this->hasMany(DetallesCompraEfectivo::class,'compra_efectivo_id');
    }
}
