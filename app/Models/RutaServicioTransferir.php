<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaServicioTransferir extends Model
{
    use HasFactory;
    protected $table = "ruta_servicio_transferirs";
    protected $fillable = ['ruta_old','ruta_new'];
}
