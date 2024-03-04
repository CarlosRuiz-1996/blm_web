<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factibilidad extends Model
{
    use HasFactory;
    protected $table = 'factibilidad';
    protected $fillable = [
        'sucursal_id',
        'user_id',
        'tiposervicio',
        'otro_tiposervicio',
        'comohacerservicio',
        'horarioservicio',
        'personalparaservicio',
        'tipoconstruccion',
        'otro_tipoconstruccion',
        'nivelproteccionlugar',
        'perimetro',
        'peatonales',
        'vehiculares',
        'ctrlacesos',
        'guardiaseg',
        'otros_guardiaseg',
        'armados',
        'corporacion_armados',
        'alarma',
        'otros_tiposenial',
        'tipoderespuesta',
        'camaras',
        'cofre',
        'descripcion_asalto',
        'tipodezona',
        'conviene',
        'observaciones',
        'status_factibilidad',
    ];

    public function image()
    {
        return $this->hasMany(FactibilidadImagen::class, 'factibilidad_id');
    }
}
