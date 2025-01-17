<?php

namespace App\Livewire\Operadores;

use App\Models\CompraEfectivo;
use App\Models\CompraEfectivoEnvases;
use App\Models\DetallesCompraEfectivo;
use App\Models\Empleado;
use App\Models\Reprogramacion;
use App\Models\Ruta;
use App\Models\RutaCompraEfectivo;
use App\Models\RutaEmpleadoReporte;
use App\Models\RutaEmpleados;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\RutaServicioTransferir;
use App\Models\RutaVehiculo;
use App\Models\RutaVehiculoReporte;
use App\Models\ServicioComision;
use App\Models\ServicioEvidenciaEntrega;
use App\Models\ServicioEvidenciaRecolecta;
use App\Models\ServicioKey;
use App\Models\ServicioPuerta;
use App\Models\ServicioRutaEnvases;
use Illuminate\Support\Facades\Notification as NotificationsNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class OperadoresIndex extends   Component
{

    public $idrecolecta;
    public $cantidadEnvases;
    public $idserviorutaEnvases;
    public $tiposervicio;
    public $statusEnvases;
    public $inputs = [];
    public $folios = [];
    public $originalFolios = [];
    public $MontoEntregado;
    public $MontoEntrega;
    #[Validate('required|numeric')]
    public $MontoRecolecta;
    public $readyToLoad = false;
    public $morralla = false;
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
    public $envasescantidad;
    public $IdservicioReprogramar;
    public $evidencias = [];
    public $motivoReprogramarConcepto;
    #[Validate(['photo.*' => 'image|max:1024000'])]
    public $photo = [];
    #[Validate(['photo.*' => 'image|max:1024000'])]
    public $photorepro;
    protected $listeners = ['modalCerrado', 'modalCerradoReprogramar'];
    public $papeleta;
    public $nServicio;
    use WithFileUploads;
    public function render()
    {
        $AutorizadoOperador = null;
        $identificado = false;
        $rutaEmpleados = [];
        $nombreUsuario = "";
        $UsuarioLogin = Auth::user();
        if ($UsuarioLogin) {
            $nombreUsuario = $UsuarioLogin->name . ' ' . $UsuarioLogin->paterno . ' ' . $UsuarioLogin->materno;
            $AutorizadoOperador = Empleado::where('user_id', $UsuarioLogin->id)->where('ctg_area_id', 18)->first();
        }
        if ($AutorizadoOperador) {
            $rutaEmpleados = Ruta::join('ruta_empleados', 'rutas.id', '=', 'ruta_empleados.ruta_id')
                ->where('rutas.ctg_rutas_estado_id', 3)
                ->where('ruta_empleados.status_ruta_empleados', 1)
                ->where('ruta_empleados.empleado_id', $AutorizadoOperador->id)
                ->get(['rutas.*']);
            $identificado = true;
        } else {
            $identificado = false;
        }


        // Pasa los resultados a la vista
        return view('livewire.operadores.operadores-index', compact('rutaEmpleados', 'nombreUsuario', 'identificado'));
    }
    public function updatedMontoRecolecta()
    {
        // Validar que sea un número y si no, asignar 0
        if (!is_numeric($this->MontoRecolecta)) {
            $this->MontoRecolecta = null;
        }
    }

    public function ModalEntregaRecolecta($id, $tiposervicio)
    {

        $this->readyToLoadModal = true;
        $servicioRuta = RutaServicio::find($id);
        $this->tiposervicio = $tiposervicio == 1 ? 'Entrega' : ($tiposervicio == 2 ? 'Recolección' : 'Otro');
        $this->papeleta = $servicioRuta->papeleta;
        $this->nServicio = $servicioRuta->id;
        $this->MontoEntrega = $servicioRuta->monto;
        $this->idrecolecta = $id;
        $this->cantidadEnvases = $servicioRuta->envases;
        // Consultar los registros de servicios_envases_rutas para esta ruta
        $serviciosEnvases = ServicioRutaEnvases::where('ruta_servicios_id', $id)->where('status_envases', 1)->get();

        // Si hay registros, llenar los arreglos con los valores recuperados
        if ($serviciosEnvases->isNotEmpty()) {
            // dd('entra');
            $this->inputs = $serviciosEnvases->mapWithKeys(function ($item) {
                return [$item->id => [
                    'cantidad' => $item->cantidad,
                    'folio' => $item->folio,
                    'photo' => '',
                    'sello' => $item->sello_seguridad,
                    'violado' => false,
                    'morralla' => false
                ]];
            })->toArray();
        } else {
            $this->inputs = [];
            if ($this->envasescantidad) {
                $limit = 1;
                if (!$this->morralla) {
                    $limit = $this->envasescantidad;
                }
                for ($i = 0; $i < $limit; $i++) {
                    $this->inputs[] = [
                        'cantidad' => '',
                        'folio' => $this->papeleta,
                        'photo' => '',
                        'sello' => '',
                        'violado' => false,
                        'morralla' => $this->morralla
                    ];
                }
            }
        }
        // dd($this->inputs);
    }

    public function envase_recolecta()
    {
        $this->validate([
            'envasescantidad' => 'required|numeric|min:1',
            'MontoRecolecta' => 'required|numeric|min:1',
        ], [
            'envasescantidad.required' => 'La cantidad de envases es obligatoria.',
            'envasescantidad.numeric' => 'La cantidad de envases debe ser un número.',
            'envasescantidad.min' => 'La cantidad de envases debe ser al menos 1.',
            'MontoRecolecta.required' => 'Debe ingresar el monto total.',
            'MontoRecolecta.numeric' => 'El monto debe ser un número.',
            'MontoRecolecta.min' => 'El monto debe ser al menos 1.',
        ]);
        $this->ModalEntregaRecolecta($this->idrecolecta, $this->tiposervicio == 'Recolección' ? 2 : 1);
    }

    public function ModalAceptar()
    {
        $this->validate([
            'idrecolecta' => 'required',
            'MontoEntrega' => 'required',
            // 'MontoEntregado' => 'required',
            'inputs.*.photo' => 'required|image|max:1024000', // Máximo 1MB
        ], [
            'inputs.*.photo.required' => 'La imagen es obligatoria',
        ]);

        try {
            DB::beginTransaction();
            $this->MontoEntregado = 0;
            foreach ($this->inputs as $index => $input) {
                $this->MontoEntregado  += (float)$input['cantidad'];
            }
            // Log::info('Entra if: ');
            if ((float)$this->MontoEntrega == (float)$this->MontoEntregado) {
                // Log::info('RutaServicio: ');
                $servicioRuta = RutaServicio::find($this->idrecolecta);
                $servicioRuta->status_ruta_servicios = 3;
                $servicioRuta->envase_cargado = 0;
                $servicioRuta->save();

                if ($servicioRuta->puertaHas) {
                    $servicioRuta->puertaHas->entrega = 1;
                    $servicioRuta->puertaHas->status_puerta_servicio = 2;
                    $servicioRuta->puertaHas->save();
                }

                //pasar servicio a 3 para poderlo seleccionar:
                $servicioRuta->servicio->status_servicio = 3;
                $servicioRuta->servicio->save();
                RutaServicioReporte::where('ruta_servicio_id', $servicioRuta->id)
                    ->where('status_ruta_servicio_reportes', 2)->update(['status_ruta_servicio_reportes' => 3]);
                //guardar fotos y la evidencia en la tabla
                foreach ($this->inputs as $index => $input) {
                    $evidencia = ServicioEvidenciaEntrega::create(['servicio_envases_ruta_id' => $index]);

                    $serv_name = 'Servicio_';
                    $path = 'evidencias/EntregasRecolectas/';
                    if ($servicioRuta->puertaHas) {
                        $serv_name = 'Puerta_';
                        $path = 'evidencias/PuertaEnPuerta/';
                    }

                    $nombreRutaGuardaImg = $serv_name . $servicioRuta->id . '_entrega_' . $evidencia->id . '_evidencia.png';

                    $input['photo']->storeAs(path: $path, name: $nombreRutaGuardaImg);
                }
                $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
                NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Entrega realizada con exito.'], ['tipomensaje' => 'success'], ['op' => 1]);
            } else {
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La cantidad Ingresada no es la cantidad a entregar:' . $this->MontoEntrega . '-' . $this->MontoEntregado], ['tipomensaje' => 'error']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Hubo un error intenta más tarde.'], ['tipomensaje' => 'error']);
        }
    }
    public function updatedEvidencias()
    {
        // Iterar sobre cada archivo cargado y almacenarlo
        foreach ($this->photo as $index => $evidencia) {
            // Verificar si el archivo se ha cargado correctamente
            if ($evidencia) {
                // Almacenar el archivo
                $path = $evidencia->store('public/evidencias');
                // Actualizar el array con la ruta del archivo almacenado
                $this->photo[$index] = $path;
            }
        }
    }


    public function modalCerrado()
    {
        $this->MontoEntrega = null;
        $this->MontoEntregado = null;
        $this->photo = null;

        $this->reset('tiposervicio', 'inputs', 'idrecolecta', 'envasescantidad', 'MontoRecolecta', 'readyToLoadModal');
    }
    public function modalCerradoReprogramar()
    {
        $this->IdservicioReprogramar = null;
        $this->motivoReprogramarConcepto = null;
        $this->photorepro = null;
    }


    public function ModalAceptarRecolecta()
    {
        $rules = [
            'idrecolecta' => 'required',
            'envasescantidad' => 'required',
            'MontoRecolecta' => 'required',
            'inputs.*.folio' => 'required',
            'inputs.*.sello' => 'required',
            'inputs.*.photo' => 'required|image|max:1024000',
        ];
        $messages = [
            'inputs.*.photo.required' => 'La imagen es obligatoria',
            'inputs.*.folio.required' => 'El folio es obligatoria',
            'inputs.*.sello.required' => 'El sello es obligatoria',
            'MontoRecolecta.required' => 'El monto es requerido',
            'envasescantidad.required' => 'La cantidad de envases es obligatoria',
        ];

        if (!$this->morralla) {
            $rules['inputs.*.cantidad'] = 'required';
            $messages['inputs.*.cantidad.required'] = 'El monto es requerido';
        }
        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();

            if (!count($this->inputs)) {
                throw new \Exception('No hay envases para guardar');
            }
            if (!$this->morralla) {
                //acumulo la cantidad de los envase
                $MontoEnvases = 0;
                $montoEnvaseViolado = 0;
                foreach ($this->inputs as $index => $input) {

                    $MontoEnvases  += (float)$input['cantidad'];
                    //si esta el monto violado se acumula para despues descontar este valor 
                    if ($input['violado']) {
                        $montoEnvaseViolado  += (float)$input['cantidad'];
                    }
                }

                if ($MontoEnvases != $this->MontoRecolecta) {
                    throw new \Exception('La suma de los envases no coinside con el monto ingresado');
                }

                //si hay violado se resta porque no se llevara.
                $this->MontoRecolecta = $MontoEnvases - $montoEnvaseViolado;
            }
            //completo datos del servicio en la ruta
            $servicioruta = RutaServicio::find($this->idrecolecta);
            $servicioruta->monto = $this->MontoRecolecta;
            $servicioruta->envases = $this->envasescantidad;
            $servicioruta->status_ruta_servicios = 3;
            $servicioruta->morralla = $this->morralla;
            $servicioruta->save();

            if ($servicioruta->puertaHas) {
                $servicioruta->puertaHas->recolecta = 1;
                $servicioruta->puertaHas->save();
            }
            $ruta = Ruta::find($servicioruta->ruta_id);
            $ruta->total_ruta = $ruta->total_ruta + $this->MontoRecolecta;
            $ruta->save();

            //pasar servicio a 3 para poderlo seleccionar:
            $servicioruta->servicio->status_servicio = 3;
            $servicioruta->servicio->save();

            foreach ($this->inputs as $index => $input) {

                $servicio_envases =  ServicioRutaEnvases::create([
                    'ruta_servicios_id' => $servicioruta->id,
                    'tipo_servicio' => 2,
                    'cantidad' => $this->morralla ? $this->MontoRecolecta : $input['cantidad'],
                    'folio' => $input['folio'],
                    'sello_seguridad' => $input['sello'],
                ]);
                $evidencia = ServicioEvidenciaRecolecta::create(
                    [
                        'servicio_envases_ruta_id' => $servicio_envases->id,
                        'violate' => $input['violado']
                    ]
                );
                $serv_name = 'Servicio_';
                $path = 'evidencias/EntregasRecolectas/';
                if ($servicioruta->puertaHas) {
                    $serv_name = 'Puerta_';
                    $path = 'evidencias/PuertaEnPuerta/';
                }
                $nombreRutaGuardaImg = $serv_name . $servicioruta->id . '_recolecta_' . $evidencia->id . '_evidencia.png';
                $input['photo']->storeAs(path: $path, name: $nombreRutaGuardaImg);
            }

            RutaServicioReporte::create([
                'servicio_id' => $servicioruta->servicio_id,
                'ruta_id' => $servicioruta->ruta_id,
                'monto' => $servicioruta->monto,
                'folio' => $servicioruta->folio,
                'envases' => $servicioruta->envases,
                'tipo_servicio' => $servicioruta->tipo_servicio,
                'area' => 3
            ]);


            $this->reset('morralla');
            // $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La recolecta se completo correctamente'], ['tipomensaje' => 'success']);
            $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
            NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La recolecta se completo correctamente.'], ['tipomensaje' => 'success'], ['op' => 1]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            $this->dispatch('error', [$e->getMessage()]);

            Log::info('Info: ' . $e);
        }
    }

    public function empezarRuta($id)
    {
        $ruta = Ruta::find($id);
        $ruta->status_ruta = 2;
        $ruta->save();
        $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
        NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta a iniciado'], ['tipomensaje' => 'success']);
    }
    public function empezarRutaServicios($contactId, $serviciotipo)
    {
        $servicioRuta = RutaServicio::where('id', $contactId)->where('tipo_servicio', $serviciotipo)->first();
        $servicioRuta->status_ruta_servicios = 2;
        $servicioRuta->created_at = now();
        $servicioRuta->save();
        if ($servicioRuta->puertaHas) {
            $servicioRuta->puertaHas->status_puerta_servicio = 1;
            $servicioRuta->puertaHas->save();
        }
        $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
        NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'El servicio a iniciado'], ['tipomensaje' => 'success']);
    }
    public function TerminarRuta($id)
    {
        $serviciosPendientes = RutaServicio::where('ruta_id', $id)
            ->where('status_ruta_servicios', 2)
            ->count();

        $ruta_compra = RutaCompraEfectivo::where('ruta_id', $id)->where('status_ruta_compra_efectivos', '=', 2)->count();

        $servicioPuertaCount = ServicioPuerta::whereHas('rutaServicio', function ($query) use ($id) {
            $query->where('ruta_id', $id)
                ->where('puerta', 1);
        })->where('status_puerta_servicio', 2)
            ->count();

        $rutaServicioCount = RutaServicio::where('ruta_id', $id)
            ->where('puerta', 1)
            ->where('status_ruta_servicios', '!=', 6)
            ->count();

        try {

            DB::beginTransaction();


            if ($serviciosPendientes > 0) {
                throw new \Exception('No se puede terminar la ruta porque aún tiene servicios pendientes');
            }
            if ($ruta_compra > 0) {
                throw new \Exception('No se puede terminar la ruta porque aún tiene compras de efectivo por terminar');
            }

            if ($servicioPuertaCount != $rutaServicioCount) {
                throw new \Exception('No se puede terminar la ruta porque aún tiene servicios de puerta en puerta');
            }

            // Si no hay servicios pendientes con estado 2, actualiza el estado de la ruta
            $ruta = Ruta::find($id);
            $ruta->status_ruta = 3;
            $ruta->hora_fin = now();
            $ruta->ctg_rutas_estado_id = 4;
            $ruta->save();
            $rutaempleados = RutaEmpleados::where('ruta_id', $id)->get();

            foreach ($rutaempleados as $rutaempleado) {
                $rutaempleado->status_ruta_empleados = 2;
                $rutaempleado->save();
            }
            foreach ($rutaempleados as $rutaempleado) {
                RutaEmpleadoReporte::create([
                    'ruta_id' => $rutaempleado->ruta_id,
                    'empleado_id' => $rutaempleado->empleado_id,
                    'status_ruta_empleado_reportes' => 2,
                ]);
            }
            $rutaVehiculos = RutaVehiculo::where('ruta_id', $id)->get();
            foreach ($rutaVehiculos as $rutaVehiculo) {
                $rutaVehiculo->status_ruta_vehiculos = 2;
                $rutaVehiculo->save();
            }
            foreach ($rutaVehiculos as $rutaVehiculo) {
                RutaVehiculoReporte::create([
                    'ruta_id' => $rutaVehiculo->ruta_id,
                    'ctg_vehiculo_id' => $rutaVehiculo->ctg_vehiculo_id,
                    'status_ruta_vehiculo_reportes' => 2,
                ]);
            }


            // Envía un mensaje de éxito
            $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
            NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta ha sido terminada'], ['tipomensaje' => 'success']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('error');
            $this->dispatch('error', [$e->getMessage()]);
        }
    }
    //status servicio ruta en 4 es reporgramnar
    //estatus servicio ruta en 3 es completa
    public function ModalReprogramarServicio($id)
    {
        $this->IdservicioReprogramar = $id;
    }

    public function ModalAceptarReprogramar()
    {
        $this->validate([
            'IdservicioReprogramar' => 'required',
            'motivoReprogramarConcepto' => 'required',
            'photorepro' => 'required',
        ]);
        $servicioRuta = RutaServicio::find($this->IdservicioReprogramar);
        $servicioRuta->status_ruta_servicios = 0;
        $servicioRuta->save();


        RutaServicioReporte::where('ruta_servicio_id', $servicioRuta->id)
            ->where('status_ruta_servicio_reportes', 2)->update(['status_ruta_servicio_reportes' => 3]);


        $repro = Reprogramacion::create([
            'motivo' => $this->motivoReprogramarConcepto,
            'ruta_servicio_id' => $servicioRuta->id,
            'area_id' => 18,
            'ruta_id_old' => $servicioRuta->ruta->id
        ]);

        $nombreEvidenciaRepro = 'reprogramacion_' . $repro->id . '.png';
        $this->photorepro->storeAs(path: 'evidencias/reprogramacion/', name: $nombreEvidenciaRepro);
        $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
        NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));

        $this->reset('photorepro', 'IdservicioReprogramar', 'motivoReprogramarConcepto');
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'El servicio sera reprogramado'], ['tipomensaje' => 'success'], ['op' => 1]);
    }


    //COMPRAS DE EFECTIVO}
    public $readyToLoadModal = false;

    public $compra_detalle;
    public $status_compra;
    public function showCompraDetail(CompraEfectivo $compra)
    {

        $this->compra_detalle = $compra;
        $this->readyToLoadModal = true;

        $this->status_compra = ($this->compra_detalle->status_compra_efectivos);
    }

    public function limpiarDatos()
    {
        // dd('limpia ');
        $this->reset('readyToLoadModal', 'compra_detalle', 'status_compra');
    }

    public function limpiarDatosDetalleCompra()
    {
        $this->reset(
            'envases_compra',
            'folio_compra',
            'monto_compra',
            'evidencia_compra',
            'inputs',
            'detalle_compra',
        );
    }
    public $detalle_compra;
    public function detalleCompraEfectivo(DetallesCompraEfectivo $compra)
    {
        $this->detalle_compra = $compra;
    }
    public $envases_compra;
    public $folio_compra;
    public $monto_compra = 0;
    public $evidencia_compra;

    public function addEnvasesCompra()
    {
        $this->validate([
            'folio_compra' => 'required',
            'envases_compra' => 'required',
            'monto_compra' => 'required|gt:0', // Debe ser mayor que 0
        ], [
            'envases_compra.required' => 'La cantidad de envases es obligatoria',
            'monto_compra.required' => 'Debe ingresar el monto total',
            'monto_compra.gt' => 'El monto debe ser mayor que 0',
            'folio_compra.required' => 'El campo es requerido',
        ]);

        $this->inputs = [];
        if ($this->envases_compra) {
            for ($i = 0; $i < $this->envases_compra; $i++) {
                $this->inputs[] = [
                    'cantidad' => '',
                    'folio' => $this->folio_compra,
                    'photo' => '',
                    'sello' => '',
                ];
            }
        }
    }
    public function finalizarCompra()
    {

        // |unique:sellos,sello
        $rules = [
            'folio_compra' => 'required',
            'monto_compra' => 'required|gt:0',
        ];

        // Si envases_compra está vacío, hacer que evidencia_compra sea requerido
        if (empty($this->envases_compra)) {
            $rules['evidencia_compra'] = 'required|image|max:1024000';
        }

        $this->validate($rules, [
            'folio_compra.required' => 'El campo es requerido',
            'monto_compra.required' => 'El campo es requerido',
            'monto_compra.gt' => 'El monto debe ser mayor que 0',
            'evidencia_compra.required' => 'El campo es requerido',
            'evidencia_compra.image' => 'El archivo debe ser una imagen',
            'evidencia_compra.max' => 'El tamaño máximo de la imagen es 100MB',

        ]);
        if ($this->envases_compra) {

            // dd('entra if');
            $this->validate(
                [
                    'inputs.*.cantidad' => 'required',
                    'inputs.*.sello' => 'required',
                    'inputs.*.photo' => 'required|image|max:1024000',

                ],
                [
                    'inputs.*.cantidad.required' => 'La cantidad es obligatoria',
                    'inputs.*.sello.required' => 'El sello es obligatoria',
                    'inputs.*.photo.required' => 'La evidencia es obligatoria',
                    'inputs.*.photo.image' => 'El archivo debe ser una imagen',
                    'inputs.*.photo.max' => 'El tamaño máximo de la imagen es 100MB',

                ]
            );
        }


        try {
            DB::beginTransaction();

            //valiar el monto...

            if ($this->monto_compra != $this->detalle_compra->monto) {
                throw new \Exception('El monto total ingresado no coinside con lo requerido');
            }

            //verificar la cantidad de los inputs
            if ($this->envases_compra) {
                $total_inputs = 0;
                foreach ($this->inputs as $index => $input) {
                    $total_inputs += $input['cantidad'];
                }
                if ($total_inputs != $this->detalle_compra->monto) {
                    throw new \Exception('El monto de los envases no coinside con lo indicado');
                }
            }
            //actualizo el detalle
            $detalle_compra = DetallesCompraEfectivo::find($this->detalle_compra->id);
            $detalle_compra->status_detalles_compra_efectivos = 2;
            $detalle_compra->save();

            //guardo la evidencia si no hay envase
            if (empty($this->envases_compra)) {
                $compra_envase = CompraEfectivoEnvases::create([
                    'detalles_compra_efectivo_id' => $detalle_compra->id,
                    'monto' => $this->monto_compra,
                    'papeleta' => $this->folio_compra,
                    'evidencia' => $this->evidencia_compra,
                ]);
                $nombreEvidenciaGuardaImg = 'compra_efectivo_detalle_' . $compra_envase->id . '.png';
                $this->evidencia_compra->storeAs(path: 'evidencias/CompraEfectivo/', name: $nombreEvidenciaGuardaImg);
            } else {
                foreach ($this->inputs as $index => $input) {

                    $compra_envase = CompraEfectivoEnvases::create([
                        'detalles_compra_efectivo_id' => $detalle_compra->id,
                        'monto' => $input['cantidad'],
                        'papeleta' => $input['folio'],
                        'sello_seguridad' => $input['sello'],
                        'evidencia' => $input['photo'],
                    ]);
                    $nombreEvidenciaGuardaImg = 'compra_efectivo_detalle_' . $compra_envase->id . '.png';
                    $input['photo']->storeAs(path: 'evidencias/CompraEfectivo/', name: $nombreEvidenciaGuardaImg);
                }
            }

            $this->limpiarDatosDetalleCompra();
            $this->showCompraDetail($detalle_compra->compra_efectivo);
            $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
            NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));
            $this->dispatch('success-compra-detalle', ['nombreArchivo' => 'Detalle de la compra finalizada.'], ['tipomensaje' => 'success']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('success-compra-detalle', ['nombreArchivo' => $e->getMessage()], ['tipomensaje' => 'error']);
        }
    }

    public function finalizarCompraEfectivo()
    {
        $compra = CompraEfectivo::find($this->compra_detalle->id);


        try {
            DB::beginTransaction();

            $detalles = DetallesCompraEfectivo::where('compra_efectivo_id', $compra->id)
                ->where('status_detalles_compra_efectivos', 1)->count();
            if ($detalles > 0) {
                throw new \Exception('Aun quedan compras por terminar.');
            }

            $compra->status_compra_efectivos = 3;
            $compra->save();

            $compra->ruta_compra->status_ruta_compra_efectivos = 3;
            $compra->ruta_compra->save();
            $users = Empleado::whereIn('ctg_area_id', [9, 2, 3, 18])->get();
            NotificationsNotification::send($users, new \App\Notifications\newNotification('Ruta Iniciada'));
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La Compra de efectivo se completo correctamente.'], ['tipomensaje' => 'success'], ['op' => 1]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => $e->getMessage() ?? 'Ha ocurrido un problema'], ['tipomensaje' => 'error']);
        }
    }

    public $keys;
    public function showKeys(RutaServicio $ruta_servicio)
    {
        $this->keys = ServicioKey::where('ruta_servicio_id', $ruta_servicio->id)->get();
    }
    public function cleanKeys()
    {
        $this->reset('keys');
    }

    // comisiones
    public $ruta_servicio_comision;
    public $cliente_comision;
    public $direccion_comision;
    public $papeleta_comision;
    public $monto_comision;
    public $evidencia_comision;
    public function addComision(RutaServicio $ruta_servicio)
    {
        $this->readyToLoadModal = true;
        $this->ruta_servicio_comision  = $ruta_servicio;
        $this->cliente_comision = $ruta_servicio->servicio->cliente->razon_social;
        $this->direccion_comision = $ruta_servicio->servicio->sucursal->sucursal->sucursal .
            ' Calle ' . $ruta_servicio->servicio->sucursal->sucursal->direccion .
            ' CP.' . $ruta_servicio->servicio->sucursal->sucursal->cp->cp .
            ' ' . $ruta_servicio->servicio->sucursal->sucursal->cp->estado->name;
    }
    public function cleanComision()
    {
        $this->reset(
            'ruta_servicio_comision',
            'cliente_comision',
            'direccion_comision',
            'papeleta_comision',
            'monto_comision',
            'evidencia_comision',
            'readyToLoadModal'
        );
    }

    public function saveComision()
    {
        $this->validate(
            [
                'papeleta_comision' => 'required',
                // 'monto_comision' => 'required',
                'evidencia_comision' => 'required|image|max:1024000'
            ],
            [
                'papeleta_comision.required' => 'El campo es Obligatorio',
                // 'monto_comision.required' => 'El campo es Obligatorio',
                'evidencia_comision.required' => 'El campo es Obligatorio',
                'evidencia_comision.image' => 'El archivo debe de ser imagen',
                'evidencia_comision.max' => 'Tamaño maximo es de 1GB'
            ]
        );
        try {

            DB::transaction(function () {


                $comision = ServicioComision::create([
                    'papeleta' => $this->papeleta_comision,
                    'monto' => $this->monto_comision??0,
                    'ruta_servicio_id' => $this->ruta_servicio_comision->id,
                ]);

                $evidenciaComisionName = 'comision_' . $comision->id . '_evidencia.png';

                $this->evidencia_comision->storeAs(path: 'evidencias/ComisionesServicios/', name: $evidenciaComisionName);


                $this->cleanComision();
            });
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La comision se guardo correctamente.'], ['tipomensaje' => 'success'], ['op' => 1]);
        } catch (Exception $e) {
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Hubo un error intenta más tarde.'], ['tipomensaje' => 'error']);
        }
    }

    public $transferir_servicio;
    public $cliente_transf;
    public $direccion_transf;
    public $transferir_servicio_tipo;
    public $papeleta_transf;
    public $monto_transf;
    public $tipo_transf;
    public $rutas;
    public function transferir(RutaServicio $ruta_servicio)
    {
        $this->readyToLoadModal = true;
        $this->transferir_servicio  = $ruta_servicio;
        $this->papeleta_transf  = $ruta_servicio->folio;
        $this->monto_transf  = $ruta_servicio->monto;
        $this->tipo_transf  = $ruta_servicio->tipo == 1 ? 'Entrega' : 'Recolecta';

        $this->cliente_transf = $ruta_servicio->servicio->cliente->razon_social;
        $this->transferir_servicio_tipo = $ruta_servicio->servicio->ctg_servicio->descripcion;
        $this->direccion_transf = $ruta_servicio->servicio->sucursal->sucursal->sucursal .
            ' Calle ' . $ruta_servicio->servicio->sucursal->sucursal->direccion .
            ' CP.' . $ruta_servicio->servicio->sucursal->sucursal->cp->cp .
            ' ' . $ruta_servicio->servicio->sucursal->sucursal->cp->estado->name;
        $this->rutas = Ruta::whereBetween('ctg_rutas_estado_id', [2, 3])->where('id', '!=', $ruta_servicio->ruta_id)->get();
    }

    public $ruta_id;
    public function saveTransfer()
    {
        $this->validate(['ruta_id' => 'required'], ['ruta_id.required' => 'campo requerido']);


        try {

            DB::transaction(function () {
                RutaServicioTransferir::create([
                    'ruta_old' => $this->transferir_servicio->ruta_id,
                    'ruta_new' => $this->ruta_id
                ]);
                $this->transferir_servicio->ruta_id = $this->ruta_id;
                $this->transferir_servicio->save();
            });
            $this->limpiarTranf();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'El servicio se transfirio correctamente.'], ['tipomensaje' => 'success'], ['op' => 1]);
        } catch (Exception $e) {
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Hubo un error intenta más tarde.'], ['tipomensaje' => 'error']);
        }
    }

    public function limpiarTranf()
{
    $this->reset(
        'transferir_servicio', 
        'cliente_transf', 
        'direccion_transf', 
        'transferir_servicio_tipo', 
        'papeleta_transf', 
        'monto_transf', 
        'tipo_transf', 
        'rutas'
    );
}

}
