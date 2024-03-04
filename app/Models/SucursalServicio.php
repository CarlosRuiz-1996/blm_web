<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalServicio extends Model
{
    use HasFactory;
    
    protected $table = 'sucursal_servicio';
    protected $fillable = ['servicio_id', 'sucursal_id', 'status_sucursal_servicio'];



    
}
