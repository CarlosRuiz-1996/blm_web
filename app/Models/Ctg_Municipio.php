<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctg_Municipio extends Model
{
    use HasFactory;
    protected $table = 'ctg_municipios';


    public function estado()
    {
        return $this->belongsTo(Ctg_Estado::class, 'ctg_estado_id');
    }
}
