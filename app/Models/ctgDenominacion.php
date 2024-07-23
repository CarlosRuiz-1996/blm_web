<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ctgDenominacion extends Model
{
    use HasFactory;
    protected $table = "ctg_denominacions";
    protected $fillable=['name','ctg_tipo_moneda_id'];

    public function tipo_moneda()
    {
        return $this->belongsTo(ctgTipoMoneda::class,'ctg_tipo_moneda_id');
    }
}
