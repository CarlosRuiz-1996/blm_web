<?php

namespace App\Livewire\Forms;

use App\Helpers\GoogleMapsHelper;
use App\Livewire\Catalogos\RutasDias;
use App\Models\Cliente;
use App\Models\CtgRutaDias;
use App\Models\CtgRutas;
use App\Models\CtgVehiculos;
use App\Models\Empleado;
use App\Models\Notification as ModelsNotification;
use App\Models\Ruta;
use App\Models\RutaEmpleados;
use App\Models\RutaFirma10M;
use App\Models\RutaServicio;
use App\Models\RutaVehiculo;
use App\Models\ServicioPuerta;
use App\Models\Servicios;
use App\Models\SucursalServicio;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class RutaForm extends Form
{
    public $hora_inicio;
    public $hora_fin;
    public $ctg_rutas_id;
    public $dia_id_calendario;
    public $ctg_ruta_dia_id;
    public $ctg_rutas_riesgo_id;
    public $ctg_rutas_estado_id;
    public $diasfiltro;
    public $diaid;
    public $botonhablitarruta = false;

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
        if ($this->diasfiltro != 0 && $this->diasfiltro != "") {
            return Ruta::where('ctg_ruta_dia_id', $this->diasfiltro)->get();
        } else {
            return Ruta::all();
        }
    }
    public function getIdDiaSiguiente()
    {
        // Obtener el día actual en español
        $nombreDiaActual = strtolower(date('l')); // 'l' devuelve el nombre completo del día en inglés

        // Array con los nombres de los días en español
        $diasSemana = [
            'sunday' => 'DOMINGO',
            'monday' => 'LUNES',
            'tuesday' => 'MARTES',
            'wednesday' => 'MIÉRCOLES',
            'thursday' => 'JUEVES',
            'friday' => 'VIERNES',
            'saturday' => 'SÁBADO'
        ];

        // Obtén el nombre del día actual en español
        $nombreDiaActual = $diasSemana[$nombreDiaActual];

        // Encuentra el índice del día actual en el array
        $indiceDiaActual = array_search($nombreDiaActual, array_values($diasSemana));

        // Incrementa el índice en 1 para obtener el índice del día siguiente (teniendo en cuenta el ciclo circular)
        $indiceDiaSiguiente = ($indiceDiaActual + 1) % 7;

        // Obtiene el nombre del día siguiente
        $nombreDiaSiguiente = array_keys($diasSemana)[$indiceDiaSiguiente];

        // Busca en el array de nombres de días en inglés el nombre del día siguiente y obtén su traducción en español
        $nombreDiaSiguienteEspañol = strtoupper($diasSemana[$nombreDiaSiguiente]);

        // Busca en la tabla de días el registro que coincida con el nombre del día siguiente y obtén su ID
        $diaSiguiente = CtgRutaDias::where('name', 'LIKE', '%' . $nombreDiaSiguienteEspañol . '%')->first();
        $idDiaSiguiente = $diaSiguiente->id;

        return Ruta::where('ctg_ruta_dia_id', $idDiaSiguiente)->get();
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

        return Servicios::with('ruta_servicios')
            ->where('cliente_id', $cliente->id)
            ->where('status_servicio', '=', 3)
            ->where(function ($query) {
                $query->whereHas('ruta_servicios')
                    ->orWhereDoesntHave('ruta_servicios');
            })
            ->get();
    }

    public function getNewServicio()
    {
        // return Cliente::withCount('servicios')
        //     ->whereHas('servicios', function ($query) {
        //         $query->where('status_servicio','=', 3);
        //     })
        //     ->get();

        return  Cliente::whereHas('servicios', function ($query) {
            $query->where('status_servicio', '=', 3);
        })
            ->with(['servicios' => function ($query) {
                $query->where('status_servicio', '=', 3);
            }])
            ->withCount(['servicios' => function ($query) {
                $query->where('status_servicio', '=', 3);
            }])
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

    public function getNextRutas()
    {
        // return Ruta::where()->get();
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

        // ->whereDoesntHave('rutas', function ($query) {
        //     $dia_semana_ruta = $this->ruta->ctg_ruta_dia_id;

        //     $query->whereHas('dia', function ($subquery) use ($dia_semana_ruta) {
        //         $subquery->where('id', $dia_semana_ruta);
        //     });
        // })
        return Servicios::where('status_servicio', '>=', 3)

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
        return RutaServicio::where('ruta_id', $this->ruta->id)->where('status_ruta_servicios', '<', 6)
            ->where(function ($query) {
                $query->where('folio', 'ilike', '%' . $this->searchServicio . '%')
                    ->orWhere('monto', 'ilike', '%' . $this->searchServicio . '%')
                    // ->orWhere('envases', 'ilike', '%' . $this->searchServicio . '%')
                ;
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
    public function storeRutaServicio($seleccionados, $seleccionadosRecolecta)
    {

        try {
            DB::beginTransaction();
            $totalRuta = 0;

            if (count($seleccionados)) {
                $ultimoServicio = null;

                foreach ($seleccionados as $index => $data) {

                    $servicioActual = Servicios::find($data['servicio_id']);
                    // Definir el origen (para el primer servicio será la dirección de salida)
                    $origen = $ultimoServicio === null
                        ? env('ORIGEN_SALIDA') // Cambia por la dirección de salida (formato "calle, colonia, cp")
                        : $ultimoServicio->direccionCompleta(); // Dirección del último servicio (definimos un método en el modelo)

                    // Destino: dirección del servicio actual
                    $destino = $servicioActual->direccionCompleta();

                    // Calcular la distancia entre el origen y el destino
                    $distancia = GoogleMapsHelper::calculateDistance($origen, $destino);



                    $servicio_ruta = RutaServicio::create([
                        'servicio_id' => $data['servicio_id'],
                        'ruta_id' => $this->ruta->id,
                        'monto' => $data['monto'],
                        'folio' => $data['folio'] ?? '',
                        'tipo_servicio' => 1,
                        'fecha_servicio' => $this->dia_id_calendario,
                        'km' => $distancia['distance'],

                    ]);

                    $servicio_ruta->servicio->status_servicio = 4;
                    $servicio_ruta->servicio->save();

                    $totalRuta += $data['monto'];

                    // Guardar el último servicio para el próximo cálculo
                    $ultimoServicio = $servicioActual;
                }
            }
            if (count($seleccionadosRecolecta)) {
                foreach ($seleccionadosRecolecta as $data) {
                    $servicio_ruta = RutaServicio::create([
                        'servicio_id' => $data['servicio_id'],
                        'ruta_id' => $this->ruta->id,
                        'monto' => $data['monto'],
                        'folio' => $data['folio'] ?? '',
                        'tipo_servicio' => 2,
                        'puerta' => $this->ruta->ctg_rutas_estado_id == 1 ? 0 : 1,
                        'status_ruta_servicios' => $this->ruta->ctg_rutas_estado_id == 1 ? 1 : 4,
                        'fecha_servicio' => $this->dia_id_calendario,
                    ]);

                    $servicio_ruta->servicio->status_servicio = 4;
                    $servicio_ruta->servicio->save();

                    if ($this->ruta->ctg_rutas_estado_id != 1) {
                        ServicioPuerta::create([
                            'ruta_servicio_id' => $servicio_ruta->id,
                        ]);
                    }
                }
            }


            $riesgo = $this->calculaRiesgo($totalRuta);
            $this->ruta->total_ruta += $totalRuta;
            $this->ruta->ctg_rutas_riesgo_id = $riesgo;
            $this->ruta->save();
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();

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
    // public $envases;
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
            $this->servicio_edit->update($this->only(['monto', 'folio'])); //, 'envases'

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
            ->whereHas('empleado', function ($query) {
                $query->where('ctg_area_id', 16)
                    ->where(function ($subquery) {
                        $subquery->where('sexo', 'ilike', '%' . $this->searchPersonalCajero . '%')
                            ->orWhere('phone', 'ilike', '%' . $this->searchPersonalCajero . '%')
                            ->orWhereHas('user', function ($userQuery) {
                                $userQuery->where('name', 'ilike', '%' . $this->searchPersonalCajero . '%')
                                    ->where('paterno', 'ilike', '%' . $this->searchPersonalCajero . '%')
                                    ->where('materno', 'ilike', '%' . $this->searchPersonalCajero . '%');
                            });
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
                // 'envases' => $this->envases,
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

    //personal cajero
    //personal
    public $searchPersonalCajeroModal;
    public function getPersonalCajero()
    {

        return Empleado::where('ctg_area_id', 17)
            ->where(function ($query) {
                $query->where('sexo', 'ilike', '%' . $this->searchPersonalCajeroModal . '%')
                    ->orWhere('phone', 'ilike', '%' . $this->searchPersonalCajeroModal . '%')
                    ->orWhereHas('user', function ($subquery) {
                        $subquery->where('name', 'ilike', '%' . $this->searchPersonalCajeroModal . '%')
                            ->where('paterno', 'ilike', '%' . $this->searchPersonalCajeroModal . '%')
                            ->where('materno', 'ilike', '%' . $this->searchPersonalCajeroModal . '%');
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

    public $searchPersonalCajero;
    public function getRutaPersonalCajero()
    {
        return RutaEmpleados::where('ruta_id', $this->ruta->id)
            ->whereHas('empleado', function ($query) {
                $query->where('ctg_area_id', 17)
                    ->where(function ($subquery) {
                        $subquery->where('sexo', 'ilike', '%' . $this->searchPersonalCajero . '%')
                            ->orWhere('phone', 'ilike', '%' . $this->searchPersonalCajero . '%')
                            ->orWhereHas('user', function ($userQuery) {
                                $userQuery->where('name', 'ilike', '%' . $this->searchPersonalCajero . '%')
                                    ->where('paterno', 'ilike', '%' . $this->searchPersonalCajero . '%')
                                    ->where('materno', 'ilike', '%' . $this->searchPersonalCajero . '%');
                            });
                    });
            })
            ->get();
    }
    //presonal operador
    public $searchPersonalOperadorModal;
    public function getPersonalOperador()
    {

        return Empleado::where('ctg_area_id', 18)
            ->where(function ($query) {
                $query->where('sexo', 'ilike', '%' . $this->searchPersonalOperadorModal . '%')
                    ->orWhere('phone', 'ilike', '%' . $this->searchPersonalOperadorModal . '%')
                    ->orWhereHas('user', function ($subquery) {
                        $subquery->where('name', 'ilike', '%' . $this->searchPersonalOperadorModal . '%')
                            ->where('paterno', 'ilike', '%' . $this->searchPersonalOperadorModal . '%')
                            ->where('materno', 'ilike', '%' . $this->searchPersonalOperadorModal . '%');
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

    public $searchPersonalOperador;
    public function getRutaPersonalOperador()
    {
        return RutaEmpleados::where('ruta_id', $this->ruta->id)
            ->whereHas('empleado', function ($query) {
                $query->where('ctg_area_id', 18)
                    ->where(function ($subquery) {
                        $subquery->where('sexo', 'ilike', '%' . $this->searchPersonalOperador . '%')
                            ->orWhere('phone', 'ilike', '%' . $this->searchPersonalOperador . '%')
                            ->orWhereHas('user', function ($userQuery) {
                                $userQuery->where('name', 'ilike', '%' . $this->searchPersonalOperador . '%')
                                    ->where('paterno', 'ilike', '%' . $this->searchPersonalOperador . '%')
                                    ->where('materno', 'ilike', '%' . $this->searchPersonalOperador . '%');
                            });
                    });
            })
            ->get();
    }


    public function validafirma10m()
    {
        return RutaFirma10M::where('ruta_id', $this->ruta->id)->get();
        // ->where('status_ruta_firma10_m_s', '=', 1)
    }

    public function insertfirma10m()
    {
        try {
            DB::beginTransaction();



            $firma =  RutaFirma10M::create([
                'ruta_id' => $this->ruta->id
            ]);

            $users = Empleado::whereIn('ctg_area_id', [2, 3])->get();
            //genera el mensaje
            $msg = 'Ser requiere validacion para que la ruta ' . $this->ruta->nombre->name . ' lleve mas de 10 millones';


            //Insertar en notificaciones de boveda
            ModelsNotification::create([
                'empleado_id_send' => Auth::user()->empleado->id,
                'ctg_area_id' => 3,
                'message' => $msg,
                'ruta_firma_id' => $firma->id,
                'tipo' => 2
            ]);
            ModelsNotification::create([
                'empleado_id_send' => Auth::user()->empleado->id,
                'ctg_area_id' => 2,
                'message' => $msg,
                'ruta_firma_id' => $firma->id,
                'tipo' => 2
            ]);

            Notification::send($users, new \App\Notifications\newNotification($msg));


            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            Log::info('Info: ' . $e);
            return 0;
        }
    }
    public function calcularProximoDia($fecha, $diaPermitido)
    {
        // Obtener el día actual (0: Domingo, 1: Lunes, ..., 6: Sábado)
        $diaActual = $fecha->format('w'); // Formato 'w' devuelve el día de la semana

        // Calcular cuántos días faltan hasta el día permitido
        $diasHastaPermitido = ($diaPermitido - $diaActual + 7) % 7;

        // Si el día permitido es hoy, no sumamos días
        if ($diasHastaPermitido === 0) {
            return $fecha; // Retorna la fecha actual
        }

        // Retorna la fecha ajustada al próximo día permitido
        return $fecha->addDays($diasHastaPermitido);
    }
}
