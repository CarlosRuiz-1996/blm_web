<?php

namespace App\Livewire\Forms;

use App\Models\Cliente;
use App\Models\CtgRutaDias;
use App\Models\CtgRutas;
use App\Models\CtgVehiculos;
use App\Models\Ruta;
use App\Models\RutaServicio;
use App\Models\RutaVehiculo;
use App\Models\Servicios;
use App\Models\SucursalServicio;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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


    //servicios
    public $searchClienteModal;
    public $searchClienteSelect;
    public function getServicios()
    {
        return SucursalServicio::with('servicio')
            ->whereHas('servicio', function ($query) {
                $query->where('status_servicio','=', 3);
            })
            ->whereHas('anexo', function ($subquery) {
                $subquery->whereHas('cliente', function ($subquerycliente) {
                    $subquerycliente->where(function ($query) {
                        $query->where('rfc_cliente', 'ilike', '%' . $this->searchClienteModal . '%')
                            ->orWhere('razon_social', 'ilike', '%' . $this->searchClienteModal . '%');
                    })
                        ->when($this->searchClienteSelect, function ($query, $searchClienteSelect) {
                            return $query->where('id', $searchClienteSelect);
                        });
                });
            })
            ->orderBy('id', 'DESC')->paginate(10);
    }

    public function getClientes()
    {
        return Cliente::where('status_cliente', 1)->get();
    }

    public function storeRutaServicio($seleccionados)
    {
        try {
            DB::beginTransaction();

            foreach ($seleccionados as $data) {

                $servicio_ruta = RutaServicio::create([
                    'servicio_id' => $data['servicio_id'],
                    'ruta_id' => $this->ruta->id,
                    'monto' => $data['monto'],
                    'folio' => $data['folio'],
                    'envases' => $data['envases'],
                ]);

               $servicio_ruta->servicio->status_servicio=4;
               $servicio_ruta->servicio->save();
            }
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            Log::info('Info: ' . $e);
            return 0;
        }
    }
}
