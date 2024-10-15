<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgRutaDias extends Model
{
    use HasFactory;
    protected $table = 'ctg_ruta_dias';
    protected $fillable = [
        'name','status_ctg_ruta_dias'
    ];



    public function rutas(){
        return $this->hasMany(Ruta::class);
    }
    public function rutasdia()
    {
        return $this->hasMany(Ruta::class, 'ctg_ruta_dia_id');  // Relación con el campo ctg_ruta_dia_id en la tabla rutas
    }
}
