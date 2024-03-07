<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factibilidad extends Model
{
    use HasFactory;
    protected $table = 'factibilidad';
    protected $fillable = [
        'cliente_id',
        'user_id',
        'status_factibilidad',
    ];

    public function image()
    {
        return $this->hasMany(FactibilidadImagen::class, 'factibilidad_id');
    }
}
