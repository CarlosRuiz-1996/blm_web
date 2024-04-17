<?php

namespace App\Livewire\Forms;

use App\Livewire\Catalogos\RutasDias;
use App\Models\Cliente;
use App\Models\CtgRutaDias;
use App\Models\CtgRutas;
use App\Models\CtgVehiculos;
use App\Models\Empleado;
use App\Models\Ruta;
use App\Models\RutaEmpleados;
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

        try {
            $this->ruta = Ruta::create($this->only(
                'hora_inicio',
                'hora_fin',
                'ctg_rutas_id',
                'ctg_ruta_dia_id',
            ));

            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getAllRutas()
    {
        return Ruta::all();
    }

    //para todos los servicios listo los clientes y despues los servicios de cada cliente.
    public function getAllServicios()
    {
        return Cliente::withCount('servicios')
            ->whereHas('servicios', function ($query) {
                $query->where('status_servicio', '>', 2);
            })
            ->get();
    }
    public function DetalleServicioCliente(Cliente $cliente)
    {
        // return Servicios::whereHas('ruta_servicios', function ($query) use ($cliente) {
        //     $query->where('cliente_id', $cliente->id)
        //         ->where('status_servicio', '>', 2);
        // })
        // ->get();

        return Servicios::with('ruta_servicios')
            ->where('cliente_id', $cliente->id)
            ->where('status_servicio', '>', 2)
            ->where(function ($query) {
                $query->whereHas('ruta_servicios')
                    ->orWhereDoesntHave('ruta_servicios');
            })
            ->get();
    }

    public function getNewServicio()
    {
        // return Servicios::where('status_servicio','=',3)->get();
        return Cliente::withCount('servicios')
            ->whereHas('servicios', function ($query) {
                $query->where('status_servicio', '=', 3);
            })
            ->get();
    }

    public function countServiciosNews()
    {
        return Servicios::where('status_servicio', '=', 3)->count();
    }

    //dias para agregar servicio a la ruta:
    public function getAllDias()
    {
        return CtgRutaDias::all();
    }

    public function boveda()
    {

        try {
            $this->ruta->ctg_rutas_estado_id = 2;
            $this->ruta->save();
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
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


        return Servicios::where('status_servicio', '=', 3)
            ->whereDoesntHave('rutas', function ($query) {
                $dia_semana_ruta = $this->ruta->ctg_ruta_dia_id;

                $query->whereHas('dia', function ($subquery) use ($dia_semana_ruta) {
                    $subquery->where('id', $dia_semana_ruta);
                });
            })
            ->whereHas('cliente', function ($subquerycliente) {
                $subquerycliente->where(function ($query) {
                    $query->where('rfc_cliente', 'ilike', '%' . $this->searchClienteModal . '%')
                        ->orWhere('razon_social', 'ilike', '%' . $this->searchClienteModal . '%');
                })
                    ->when($this->searchClienteSelect, function ($query, $searchClienteSelect) {
                        return $query->where('id', $searchClienteSelect);
                    });
            })

            ->orderBy('id', 'DESC')->paginate(10);
    }
    public $searchServicio;
    public function getRutaServicios()
    {
        return RutaServicio::where('ruta_id', $this->ruta->id)
            ->where(function ($query) {
                $query->where('folio', 'ilike', '%' . $this->searchServicio . '%')
                    ->orWhere('monto', 'ilike', '%' . $this->searchServicio . '%')
                    ->orWhere('envases', 'ilike', '%' . $this->searchServicio . '%');
            })
            ->whereHas('servicio', function ($query) {
                $query->where(function ($subquery) {
                    $subquery->whereHas('ctg_servicio', function ($subsubquery) {
                        $subsubquery->where('descripcion', 'ilike', '%' . $this->searchServicio . '%')
                            ->orWhere('folio', 'ilike', '%' . $this->searchServicio . '%');
                    });
                })
                    ->orWhereHas('cliente', function ($subquery) {
                        $subquery->where('rfc_cliente', 'ilike', '%' . $this->searchServicio . '%')
                            ->orWhere('razon_social', 'ilike', '%' . $this->searchServicio . '%');
                    });
            })
            ->get();
    }
    public function getClientes()
    {
        return Cliente::where('status_cliente', 1)->get();
    }

    public function calculaRiesgo($totalRuta)
    {
        if ($totalRuta >= 0 && $totalRuta <= 6999999.99) {
            return 4;
        } else if ($totalRuta >= 7000000 && $totalRuta <= 9999999.99) {
            return 3;
        } else if ($totalRuta >= 10000000) {
            return 2;
        }
    }
    public function storeRutaServicio($seleccionados)
    {
        try {
            DB::beginTransaction();
            $totalRuta = 0;

            foreach ($seleccionados as $data) {

                $servicio_ruta = RutaServicio::create([
                    'servicio_id' => $data['servicio_id'],
                    'ruta_id' => $this->ruta->id,
                    'monto' => $data['monto'],
                    'folio' => $data['folio'],
                    'envases' => $data['envases'],
                ]);

                $servicio_ruta->servicio->status_servicio = 4;
                $servicio_ruta->servicio->save();

                $totalRuta += $data['monto'];
            }


            //calcular riesgo de la ruta:
            $riesgo = $this->calculaRiesgo($totalRuta);
            //guardo el monto de mi ruta.
            $this->ruta->total_ruta += $totalRuta;
            $this->ruta->ctg_rutas_riesgo_id = $riesgo;
            $this->ruta->save();
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            Log::info('Info: ' . $e);
            return 0;
        }
    }

    public function deleteServicio($servicio)
    {

        try {
            DB::beginTransaction();
            //actualizo el status del servicio para que se seleccione de nuevo
            $servicio->servicio->status_servicio = 3;
            $servicio->servicio->save();


            //actualizo el monto de la ruta y riesgo
            $this->ruta->total_ruta -= $servicio->monto;
            $riesgo = $this->calculaRiesgo($this->ruta->total_ruta);
            $this->ruta->ctg_rutas_riesgo_id = $riesgo;
            $this->ruta->save();
            //elimino de la tabla ruta_servicio
            $servicio->delete();
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();

            return 0;
        }
    }

    public $monto;
    public $folio;
    public $envases;
    public $servicio_desc;
    public $servicio_edit;
    public function updateServicio()
    {

        try {
            DB::beginTransaction();

            //actualizo el monto de la ruta y riesgo
            $this->ruta->total_ruta -= $this->servicio_edit->monto;
            $this->ruta->total_ruta += $this->monto;
            $riesgo = $this->calculaRiesgo($this->ruta->total_ruta);
            $this->ruta->ctg_rutas_riesgo_id = $riesgo;
            $this->ruta->save();
            $this->servicio_edit->update($this->only(['monto', 'folio', 'envases']));

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            return 0;
        }
    }


    //personal de segurdad
    public $searchPersonalModal;
    public function getPersonal()
    {

        return Empleado::where('ctg_area_id', 16)
            ->where(function ($query) {
                $query->where('sexo', 'ilike', '%' . $this->searchPersonalModal . '%')
                    ->orWhere('phone', 'ilike', '%' . $this->searchPersonalModal . '%')
                    ->orWhereHas('user', function ($subquery) {
                        $subquery->where('name', 'ilike', '%' . $this->searchPersonalModal . '%')
                            ->where('paterno', 'ilike', '%' . $this->searchPersonalModal . '%')
                            ->where('materno', 'ilike', '%' . $this->searchPersonalModal . '%');
                    });
            })
            ->whereDoesntHave('rutas', function ($query) {
                // Obtener el día de la semana de la ruta
                $dia_semana_ruta = $this->ruta->ctg_ruta_dia_id;

                $query->whereHas('dia', function ($subquery) use ($dia_semana_ruta) {
                    $subquery->where('id', $dia_semana_ruta);
                });
            })
            ->paginate(10);
    }

    public $searchPersonal;
    public function getRutaPersonal()
    {
        // return RutaEmpleados::where('ctg_area_id', 16)->paginate(10);


        return RutaEmpleados::where('ruta_id', $this->ruta->id)
            ->WhereHas('empleado', function ($query) {
                $query->where('sexo', 'ilike', '%' . $this->searchPersonal . '%')
                    ->orWhere('phone', 'ilike', '%' . $this->searchPersonal . '%')

                    ->orWhereHas('user', function ($subquery) {
                        $subquery->where('name', 'ilike', '%' . $this->searchPersonal . '%')
                            ->where('paterno', 'ilike', '%' . $this->searchPersonal . '%')
                            ->where('materno', 'ilike', '%' . $this->searchPersonal . '%');
                    });
            })
            ->get();
    }

    public function storePersonal($empleado_id)
    {
        RutaEmpleados::create([
            'ruta_id' => $this->ruta->id,
            'empleado_id' => $empleado_id
        ]);
    }

    public function deletePersonal($empleado)
    {
        $empleado->delete();
    }


    //asignar servicio a la ruta:

    public $ruta_id;
    public function storeServicioRuta($servicio_id)
    {
        try {
            DB::beginTransaction();


            $servicio_ruta = RutaServicio::create([
                'servicio_id' => $servicio_id,
                'ruta_id' => $this->ruta_id,
                'monto' => $this->monto,
                'folio' => $this->folio,
                'envases' => $this->envases,
            ]);

            $servicio_ruta->servicio->status_servicio = 4;
            $servicio_ruta->servicio->save();


            $ruta = Ruta::find($this->ruta_id);

            $totalRuta = $ruta->total_ruta + $this->monto;
            //calcular riesgo de la ruta:
            $riesgo = $this->calculaRiesgo($totalRuta);
            //guardo el monto de mi ruta.
            $ruta->total_ruta = $totalRuta;
            $ruta->ctg_rutas_riesgo_id = $riesgo;
            $ruta->save();


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
