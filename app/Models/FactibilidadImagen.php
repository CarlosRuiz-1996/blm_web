<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactibilidadImagen extends Model
{
    use HasFactory;
    protected $table = 'factibilidad_img';
    protected $fillable = ['imagen', 'factibilidad_id'];

}
