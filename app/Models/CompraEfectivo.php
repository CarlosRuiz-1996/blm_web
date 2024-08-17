<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraEfectivo extends Model
{
    use HasFactory;
    protected $table = "compra_efectivos";
    protected $fillable = [
        'total',
        'fecha_compra',
        'status_compra_efectivos'
    ];



    public function detalles(){
        return $this->hasMany(DetallesCompraEfectivo::class,'compra_efectivo_id');
    }


    public function ruta_compra(){
        return $this->hasOne(RutaCompraEfectivo::class, 'compra_efectivo_id');
    }
}
