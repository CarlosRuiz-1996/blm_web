<?php

namespace App\Livewire\Forms;

use App\Models\CtgRutaDias;
use App\Models\CtgRutas;
use App\Models\CtgVehiculos;
use App\Models\Ruta;
use App\Models\RutaVehiculo;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RutaForm extends Form
{
    public $hora_inicio;
    public $hora_fin;
    public $ctg_rutas_id;
    public $ctg_ruta_dia_id;
    public $ctg_rutas_riesgo_id;
    public $ctg_rutas_estado_id;

    //para poder guardardar el model de la ruta
    public $ruta;

    // protected $rules = [
    //     'hora_inicio' => 'required',
    //     // 'hora_fin' => 'required',
    //     'ctg_rutas_id' => 'required',
    //     'ctg_ruta_dia_id' => 'required',
    //     // 'ctg_rutas_riesgo_id' => 'required',
    //     // 'ctg_rutas_estado_id' => 'required',
    // ];

    public function getCtgRutas($dia_id)
    {

        return CtgRutas::doesntHave('rutas', 'and', function ($query) use ($dia_id) {
            $query->where('ctg_ruta_dia_id', $dia_id);
        })
            ->get();
    }

    public function getCtgDias()
    {
        return CtgRutaDias::all();
    }

    public function store()
    {
        $this->ruta = Ruta::create($this->only(
            'hora_inicio',
            'hora_fin',
            'ctg_rutas_id',
            'ctg_ruta_dia_id',
        ));
    }

    public function getAllRutas()
    {
        return Ruta::all();
    }

    //vehiculos
    public $searchVehiculoModal;
    public function getVehiculos($sort, $orderBy, $list)
    {

        // Obtener el día de la semana de la ruta
        $dia_semana_ruta = $this->ruta->ctg_ruta_dia_id;

        // Obtener los vehículos disponibles para la ruta en el día de la semana
        return CtgVehiculos::whereDoesntHave('rutas', function ($query) use ($dia_semana_ruta) {
            $query->whereHas('dia', function ($subquery) use ($dia_semana_ruta) {
                $subquery->where('id', $dia_semana_ruta);
            });
        })->where(function ($query) {
            $query->where('placas', 'ilike', '%' . $this->searchVehiculoModal . '%')
                ->orWhere('descripcion', 'ilike', '%' . $this->searchVehiculoModal . '%')
                ->orWhere('serie', 'ilike', '%' . $this->searchVehiculoModal . '%')
                ->orWhere('anio', 'ilike', '%' . $this->searchVehiculoModal . '%')
                ->orWhereHas('modelo', function ($subquery) {
                    $subquery->where('name', 'ilike', '%' . $this->searchVehiculoModal . '%')
                        ->orWhereHas('marca', function ($subsubquery) {
                            $subsubquery->where('name', 'ilike', '%' . $this->searchVehiculoModal . '%');
                        });
                });
        })
            ->orderBy($sort, $orderBy)
            ->paginate($list);
    }
    public $searchVehiculo;

    public function getRutaVehiculos()
    {
        return RutaVehiculo::where('ruta_id', $this->ruta->id)
            ->WhereHas('vehiculo', function ($query) {
                $query->where('placas', 'ilike', '%' . $this->searchVehiculo . '%')
                    ->orWhere('descripcion', 'ilike', '%' . $this->searchVehiculo . '%')
                    ->orWhere('serie', 'ilike', '%' . $this->searchVehiculo . '%')
                    ->orWhere('anio', 'ilike', '%' . $this->searchVehiculo . '%')
                    ->orWhereHas('modelo', function ($subquery) {
                        $subquery->where('name', 'ilike', '%' . $this->searchVehiculo . '%')
                            ->orWhereHas('marca', function ($subsubquery) {
                                $subsubquery->where('name', 'ilike', '%' . $this->searchVehiculo . '%');
                            });
                    });
            })
            ->get();
    }
    public function storeVehiculos($vehiculo_id)
    {
        RutaVehiculo::create([
            'ruta_id' => $this->ruta->id,
            'ctg_vehiculo_id' => $vehiculo_id
        ]);
    }

    public function deleteVehiculos($vehiculo)
    {
        $vehiculo->delete();
    }
}
