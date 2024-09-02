<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoArmado extends Model
{
    use HasFactory;
    protected $table = 'empleado_armados';
    protected $fillable = [
        'empleado_id'
    ];
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
