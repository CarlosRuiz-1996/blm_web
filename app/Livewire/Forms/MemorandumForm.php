<?php

namespace App\Livewire\Forms;

use App\Models\CtgConsignatario;
use App\Models\CtgDiaEntrega;
use App\Models\CtgDiaServicio;
use App\Models\CtgHorarioEntrega;
use App\Models\Ctg_Horario_Servicio;
use App\Models\CtgTipoServicio;
use App\Models\CtgTipoSolicitud;
use App\Models\Factibilidad;
use App\Models\Memorandum;
use App\Models\MemorandumCotizacion;
use App\Models\MemorandumServicios;
use App\Models\MemorandumValidacion;
use App\Models\SucursalServicio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Livewire\Form;

class MemorandumForm extends Form
{
    //factibilidad.
    public $factibilidad;

    public $razon_social;
    public $rfc_cliente;
    public $ejecutivo;
    public $fecha_solicitud;

    public $grupo;
    public $ctg_tipo_solicitud_id;
    public $ctg_tipo_servicio_id;
    public $observaciones;
    public $cliente_id;

    //catalogos
    public function getAllTipoSolicitud()
    {
        return CtgTipoSolicitud::all();
    }

    public function getAllTipoServicio()
    {
        return CtgTipoServicio::all();
    }

    public function getAllHorarioEntega()
    {
        return CtgHorarioEntrega::all();
    }

    public function getAllDiaEntega()
    {
        return CtgDiaEntrega::all();
    }
    public function getAllHorarioServicio()
    {
        return Ctg_Horario_Servicio::all();
    }

    public function getAllDiaServicio()
    {
        return CtgDiaServicio::all();
    }
    public function getAllConsignatorio()
    {
        return CtgConsignatario::all();
    }


    //tablas

    public function getFactibilidadAll()
    {
        return Factibilidad::where('status_factibilidad', '=', 1)
        ->whereHas('anexo', function ($query) {
            $query->where('cotizacion_id','!=', null);
        })
        ->get();
    }
    public function getMemorandumValidacion()
    {
        // return Memo::whereHas('memorandum', function ($query) {
        //     $query->where('status_memoranda', 1);
        // })->get();
        return Memorandum::where('status_memoranda', 1)->get();
    }
    public function getMemorandumTerminado()
    {
        return Memorandum::where('status_memoranda', 2)
            ->whereHas('memo_cotizacion') // Filtra los que tienen una cotización relacionada
            ->paginate(10);
    }
    public function getSucursales($id)
    {

        $sucursal_servicio = SucursalServicio::with('sucursal', 'servicio')
            ->where('anexo1_id', $id)
            ->get();

        $sucursales = [];
        foreach ($sucursal_servicio as $ss) {
            $sucursal = $ss->sucursal;
            $servicio = $ss->servicio;

            // Agregar la sucursal al arreglo de sucursales si aún no está presente
            if (!array_key_exists($sucursal->id, $sucursales)) {
                $sucursales[$sucursal->id] = [
                    'sucursal' => $sucursal,
                    'servicios' => []
                ];
            }

            // Agregar el servicio al arreglo de servicios de la sucursal
            // $sucursales[$sucursal->id]['servicios'][] = $servicio;
            $sucursales[$sucursal->id]['servicios'][] = [
                'id' => $ss->id, // ID de la relación sucursal_servicio
                'servicio' => $servicio,
            ];

            $this->sucursal_serviciosIds[] = [
                'servicio_id' => $servicio->id, //id del servicio
                'sucursal_servicio_id' => $ss->id, //id de sucursal_servicio  
            ];
        }


        return $sucursales;
    }
    public function setMemoDetalles($memorandum)
    {

        $this->razon_social = $memorandum->cliente->razon_social;
        $this->rfc_cliente = $memorandum->cliente->rfc_cliente;
        $this->fecha_solicitud = $memorandum->created_at;
        $this->grupo = $memorandum->grupo;
        $this->ctg_tipo_solicitud_id = $memorandum->tipo_solicitud->name;
        $this->ctg_tipo_servicio_id = $memorandum->tipo_servicio->name;
        $this->observaciones = $memorandum->observaciones;

        //reviso si la sucursal se repite y las guardo en un arreglo para la vista
        $sucursales = [];
        foreach ($memorandum->memo_cotizacion->cotizacion->anexo->sucursal_servicio as $suc) {
            $sucursal = $suc->sucursal;
            if (!in_array($sucursal, $sucursales)) {
                $sucursales[] = $sucursal;
            }
        }
        return $sucursales;
    }
    // inserta memo
    public $horarioEntrega = [];
    public $diaEntrega = [];
    public $horarioServicio = [];
    public $diaServicio = [];
    public $consignatorio = [];
    public $sucursal_serviciosIds = [];

    public function rules()
    {
        $rules = [];
        foreach ($this->sucursal_serviciosIds as $index => $servicioId) {
            $rules["horarioEntrega.{$servicioId['servicio_id']}"] = 'required';
        }
        foreach ($this->sucursal_serviciosIds as $index => $servicioId) {
            $rules["diaEntrega.{$servicioId['servicio_id']}"] = 'required';
        }
        foreach ($this->sucursal_serviciosIds as $index => $servicioId) {
            $rules["horarioServicio.{$servicioId['servicio_id']}"] = 'required';
        }
        foreach ($this->sucursal_serviciosIds as $index => $servicioId) {
            $rules["diaServicio.{$servicioId['servicio_id']}"] = 'required';
        }
        foreach ($this->sucursal_serviciosIds as $index => $servicioId) {
            $rules["consignatorio.{$servicioId['servicio_id']}"] = 'required';
        }
        $rules["grupo"] = 'required';
        $rules["ctg_tipo_solicitud_id"] = 'required';
        $rules["ctg_tipo_servicio_id"] = 'required';
        // dd($rules);
        return $rules;
    }


    public function messages()
    {
        return [
            'horarioEntrega.*.required' => 'Por favor, seleccione un horario de entrega.',
            'diaEntrega.*.required' => 'Por favor, seleccione un dia de entrega.',
            'horarioServicio.*.required' => 'Por favor, seleccione un horario de servicio.',
            'diaServicio.*.required' => 'Por favor, seleccione un dia de servicio.',
            'consignatorio.*.required' => 'Por favor, seleccione un consignatorio.',
            'grupo.required' => 'El campo Grupo es obligatorio.',
            'ctg_tipo_solicitud_id.required' => 'El campo tipo de solicitud es obligatorio.',
            'ctg_tipo_servicio_id.required' => 'El campo tipo de servicio es obligatorio.',
        ];
    }
    public function store()
    {

        try {
            DB::beginTransaction();
            //creo el memorandum

            $this->validate();

            $this->cliente_id = $this->factibilidad->cliente_id;
            Log::info('inserta memo');
            $memorandum = Memorandum::create($this->only(['grupo', 'ctg_tipo_solicitud_id', 'ctg_tipo_servicio_id', 'observaciones', 'cliente_id']));


            Log::info('inserta memo cotizacion');
            //si existe una cotizacion creo una relacion entre cotizacion y memorandum 
            if ($this->factibilidad->anexo->cotizacion_id) {
                MemorandumCotizacion::create([
                    'memoranda_id' => $memorandum->id,
                    'cotizacion_id' => $this->factibilidad->anexo->cotizacion_id,
                ]);
            }


            $datos = [];
            Log::info('recorro array data');
            //obtengo los datos para crear la tabla memo_servicio
            foreach ($this->horarioEntrega as $index => $horario) {
                $sucursalservicioId = 0;
                foreach ($this->sucursal_serviciosIds as $servicio) {
                    if ($servicio['servicio_id'] === $index) {
                        $sucursalservicioId = $servicio['sucursal_servicio_id'];
                        break;
                    }
                }

                $datos[] = [
                    'sucursal_servicio_id' => $sucursalservicioId,
                    'horarioEntrega' => $horario,
                    'diaEntrega' => $this->diaEntrega[$index],
                    'horarioServicio' => $this->horarioServicio[$index],
                    'diaServicio' => $this->diaServicio[$index],
                    'consignatorio' => $this->consignatorio[$index],
                ];
            }

            Log::info('guardo memo servicio');
            // guardo el complemento de los servicios. 
            foreach ($datos as $dato) {
                MemorandumServicios::create([
                    'sucursal_servicio_id' => $dato['sucursal_servicio_id'],
                    'memoranda_id' => $memorandum->id,
                    'ctg_dia_servicio_id' => $dato['horarioEntrega'],
                    'ctg_dia_entrega_id' => $dato['diaEntrega'],
                    'ctg_horario_servicio_id' => $dato['horarioServicio'],
                    'ctg_horario_entrega_id' => $dato['diaServicio'],
                    'ctg_consignatario_id' => $dato['consignatorio'],
                    // Agrega aquí cualquier otro campo que necesites guardar en la tabla Sucursal_Detalles
                ]);
            }

            Log::info('actualizo actibilidad');
            $this->factibilidad->status_factibilidad = 2;
            $this->factibilidad->update();

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            $this->validate();
            DB::rollBack();
            Log::error('Error al intentar guardar los datos: ' . $e->getMessage());
            return 0;
        }
    }


    // firmas
    public function getFirmas($memo_id)
    {
        return MemorandumValidacion::where('memoranda_id', $memo_id)->get();
    }


    //guardar validacion del memorandum
    public function storeFinalizar(Memorandum $memo)
    {

        try {
            DB::beginTransaction();

            //finalizo el memorandum
            $memo->status_memoranda = 2;
            $memo->save();

            //finalizo la cotizacion
            $memo->memo_cotizacion->cotizacion->status_cotizacion = 5;
            $memo->memo_cotizacion->cotizacion->save();


            //actualizo los servicios que tengan sucursal a 3, para listarlos en rutas
            $memo->memo_servicio()
                ->whereHas('sucursal_servicio.servicio', function ($query) {
                    $query->where('status_servicio', 2);
                })
                ->each(function ($servicio) {
                    $servicio->sucursal_servicio->servicio->status_servicio = 3;
                    $servicio->sucursal_servicio->servicio->save();
                });

            //se vuelve cliente activo
            $memo->cliente->status_cliente = 1;
            $memo->cliente->save();

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al intentar guardar los datos: ' . $e->getMessage());
            return 0;
        }
    }
}
