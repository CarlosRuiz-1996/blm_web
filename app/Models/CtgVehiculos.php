<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtgVehiculos extends Model
{
    use HasFactory;
    protected $table = 'ctg_vehiculos';
    protected $fillable = [
        'descripcion',
        'serie',
        'anio',
        'ctg_vehiculo_modelo_id',
        'placas',
        'status_ctg_vehiculos',
        'litro_km',
        'tipo_combustible'
    ];


    public function modelo()
    {
        return $this->belongsTo(CtgVehiculosModelo::class, 'ctg_vehiculo_modelo_id');
    }


    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_vehiculos', 'ctg_vehiculo_id', 'ruta_id');
    }

    // contar cuanto se ha gastado en km 
    public function servicios_rutas()
    {
        return $this->hasMany(CtgVehiculosRutaServicios::class, 'ctg_vehiculo_id');
    }

    public function kilometrosTotales($fechaInicio = null, $fechaFin = null)
    {
        $query = $this->servicios_rutas();

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        return $query->sum('km');
    }

    public function calcularCostoGasolina($fechaInicio = null, $fechaFin = null)
    {
        $query = $this->servicios_rutas();

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        return $query->get()->sum(function ($vehiculoServicio) {

            $type = 0;
            switch ($this->tipo_combustible) {
                case 1:
                    $type = 'Magna';
                    break;
                case 2:
                    $type = 'Premium';
                    break;
                case 3:
                    $type = 'Diesel';
                    break;
            }


            $precioGasolina = FuelPrice::where('fecha', $vehiculoServicio->created_at->toDateString())
                ->where('type', $type)
                ->value('price');

            return ($vehiculoServicio->km / $this->litro_km) * ($precioGasolina ?? 0); // Considera precio 0 si no hay dato
        });
    }
}
