<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactibilidadRpt extends Model
{
    use HasFactory;
    protected $table = 'factibilidad_rpt';
    protected $fillable = [
        'sucursal_id',
        'factibilidad_id',
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
        'tiposenial',
        'otros_tiposenial',
        'tipoderespuesta',
        'tipodefalla',
        'camaras',
        'cofre',
        'descripcion_asalto',
        'tipodezona',
        'conviene',
        'observaciones',
        'status_factibilidad_rpt',
    ];

    public function image()
    {
        return $this->hasMany(FactibilidadImagen::class, 'factibilidad_rpt_id');
    }
    public function horario()
    {
        return $this->belongsTo(Ctg_Horario_Servicio::class, 'horarioservicio');
    }

    public function factibilidad()
    {
        return $this->belongsTo(Factibilidad::class, 'factibilidad_id');
    }
}
