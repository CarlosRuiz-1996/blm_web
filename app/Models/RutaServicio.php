<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class RutaServicio extends Model
{
    use HasFactory;
    protected $table = "ruta_servicios";
    protected $fillable = [
        'servicio_id',
        'ruta_id',
        'monto',
        'folio',
        'envases',
        'tipo_servicio',
        'status_ruta_servicios',
        'envase_cargado',
        'keys',
        'morralla',
        'puerta',
        'fecha_servicio',
        'km'
    ];


    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }


    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function envases_servicios()
    {
        return $this->hasMany(ServicioRutaEnvases::class, 'ruta_servicios_id');
    }


    // public function envase_servicio(){
    //     return $this->hasOne(ServicioRutaEnvases::class, 'ruta_servicios_id');
    // }

    public function keys()
    {
        return $this->hasMany(ServicioKey::class, 'ruta_servicio_id');
    }
    public function servicioKeys()
    {
        return $this->hasMany(ServicioKey::class, 'ruta_servicio_id');
    }
    public function comision()
    {
        return $this->hasMany(ServicioComision::class, 'ruta_servicio_id');
    }

    public function puertaHas()
    {
        return $this->hasOne(ServicioPuerta::class, 'ruta_servicio_id');
    }


    public function vehiculo_servicio()
    {
        return $this->hasMany(CtgVehiculosRutaServicios::class, 'ruta_servicio_id');
    }

    public function kilometrosTotales($fechaInicio = null, $fechaFin = null)
    {
        $query = $this->vehiculo_servicio();

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        return $query->sum('km');
    }



    public function calcularCostoGasolina($fechaInicio = null, $fechaFin = null)
    {
        $query = $this->vehiculo_servicio();

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        return $query->get()->sum(function ($vehiculoServicio) {

            Log::info($vehiculoServicio);
            $type=0;
            switch ($vehiculoServicio->vehiculoRuta->tipo_combustible){
                case 1: $type = 'Magna';break;
                case 2: $type = 'Premium';break;
                case 3: $type = 'Diesel';break;
            }

          
            $precioGasolina = FuelPrice::where('fecha', $vehiculoServicio->created_at->toDateString())
                ->where('type', $type)
                ->value('price');

            return ($vehiculoServicio->km/$vehiculoServicio->vehiculoRuta->litro_km) * ($precioGasolina ?? 0); // Considera precio 0 si no hay dato
        });
    }
}
