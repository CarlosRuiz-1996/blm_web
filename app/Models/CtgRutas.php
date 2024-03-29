<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgRutas extends Model
{
    use HasFactory;
    protected $table = 'ctg_rutas';
    protected $fillable = [
        'name','status_ctg_ruta'
    ];
    public function rutas()
    {
        return $this->hasMany(Ruta::class, 'ctg_rutas_id');
    }
}
