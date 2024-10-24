<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisorArea extends Model
{
    use HasFactory;
                        
    protected $table = 'revisor_areas';
    protected $fillable = [
        'empleado_id',
        'ctg_area_id',
        'status_revisor_areas',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function area()
    {
        return $this->belongsTo(Ctg_Area::class, 'ctg_area_id');
    }
}
