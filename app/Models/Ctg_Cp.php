<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctg_Cp extends Model
{
    use HasFactory;
    protected $table = 'ctg_cp';


    public function municipio()
    {
        return $this->belongsTo(Ctg_Municipio::class, 'ctg_municipio_id');
    }

    public function estado()
    {
        return $this->belongsTo(Ctg_Estado::class, 'ctg_estado_id');
    }
}
