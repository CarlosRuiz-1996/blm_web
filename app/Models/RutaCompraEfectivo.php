<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaCompraEfectivo extends Model
{
    use HasFactory;
    protected $fillable = [
        'ruta_id',
        'compra_efectivo_id',
        'status_ruta_compra_efectivos',
    ];
    protected $table = "ruta_compra_efectivos";

    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function compra(){
        return $this->belongsTo(CompraEfectivo::class, 'compra_efectivo_id');
    }
}
