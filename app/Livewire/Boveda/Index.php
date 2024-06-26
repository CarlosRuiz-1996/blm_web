<?php

namespace App\Livewire\Boveda;

use App\Livewire\Operaciones\RutaGestion;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Notification;
use App\Models\ResguardoResporte;
use App\Models\Ruta;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\ServicioRutaEnvases;
use App\Models\Servicios;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationsNotification;

class Index extends Component
{
    public $serviciosRuta;
    public $motivoNo;
    public $idservioruta;
    public $idserviorutaEnvases;
    public $idserviorutacancelado;
    public $cantidadEnvases;
    public $statusEnvases;
    public $inputs = [];
    public $sellos = [];
    public $readyToLoad = false;

    public function render()
    {
        $resguardototal = Cliente::where('status_cliente', 1)->sum('resguardo');
        $Movimientos =  RutaServicioReporte::paginate(10);
        $servicios = Ruta::where('ctg_rutas_estado_id', 2)->paginate(10);
        return view('livewire.boveda.index', compact('servicios', 'Movimientos', 'resguardototal'));
    }
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
    public $ruta_id;
    public function llenarmodalservicios($idruta)
    {
        $this->ruta_id = $idruta;
        $this->serviciosRuta = RutaServicio::where('ruta_id', $idruta)->get();
    }
    public function cargar($idservicioruta, $rutaId)
    {
        $servicioRuta = RutaServicio::find($idservicioruta);

        // $serviciosRutaAll = RutaServicio::where('ruta_id', $rutaId)->get();
        if ($servicioRuta->envase_cargado!=0) {
            try {
                DB::beginTransaction();
                $ClienteResguardo = $servicioRuta->servicio->cliente->resguardo;
                if ($ClienteResguardo >= $servicioRuta->monto) {
                    $servicioRuta->update(['status_ruta_servicios' => 2]);
                    $this->llenarmodalservicios($servicioRuta->ruta_id); // Actualiza los datos
                    // $servicioRutastatus2 = RutaServicio::where('ruta_id', $rutaId)->where('status_ruta_servicios', 2)->get();

                    // Crear un nuevo objeto RutaServicioReporte
                    $rutaServicioReporte = new RutaServicioReporte();

                    // Asignar valores del servicio actualizado al reporte
                    $rutaServicioReporte->servicio_id = $servicioRuta->servicio_id;
                    $rutaServicioReporte->ruta_id = $servicioRuta->ruta_id;
                    $rutaServicioReporte->monto = $servicioRuta->monto;
                    $rutaServicioReporte->folio = $servicioRuta->folio;
                    $rutaServicioReporte->envases = $servicioRuta->envases;
                    $rutaServicioReporte->tipo_servicio = $servicioRuta->tipo_servicio;
                    $rutaServicioReporte->status_ruta_servicio_reportes = $servicioRuta->status_ruta_servicios; // Igualamos al status actualizado del servicio
                    // Guardar el nuevo registro en la base de datos
                    $rutaServicioReporte->save();
                    ResguardoResporte::create([
                        'servicio_id' => $servicioRuta->servicio_id,
                        'resguardo_actual' => $cliente = $servicioRuta->servicio->cliente->resguardo,
                        'cantidad' => $servicioRuta->monto,
                        'tipo_servicio' => 1,
                        'status_reporte_resguardo' => 1,
                    ]);
                    $cliente = $servicioRuta->servicio->cliente;
                    // Modificar la propiedad 'resguardo'
                    $cliente->resguardo = $cliente->resguardo - $servicioRuta->monto;  // Cambia 'NuevoValor' al valor que desees
                    // Guardar los cambios en la base de datos
                    $cliente->save();
                } else {

                    $rfc = $servicioRuta->servicio->cliente->rfc_cliente;
                    $msg = "El Servicio con id: $servicioRuta->servicio_id para el cliente con rfc $rfc no cuenta con saldo suficiente";
                    Notification::create([
                        'empleado_id_send' => Auth::user()->empleado->id,
                        'ctg_area_id' => 2,
                        'message' => $msg,
                        'tipo' => 1
                    ]);
                    Notification::create([
                        'empleado_id_send' => Auth::user()->empleado->id,
                        'ctg_area_id' => 19,
                        'message' => $msg,
                        'tipo' => 1
                    ]);
                    $users = Empleado::whereIn('ctg_area_id', [19, 2])->get();
                    NotificationsNotification::send($users, new \App\Notifications\newNotification($msg));
                    $this->dispatch('ServicioResguardo', ['No cuenta con dinero en resguardo,Se notificara a las areas Correspondientes', 'error']);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('successservicioEnvases', ['Ocurrio un error intenta mas tarde.', 'error']);
                // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
                // Log::info('Info: ' . $e);
            }
        }else{
            $this->dispatch('errorTabla', ['Debes de cargar los envases para este servicio.']);

        }
    }

    public function finailzar()
    {

        $serviciosRutaAll = RutaServicio::where('ruta_id', $this->ruta_id)->count();
        $servicioRutastatus2 = RutaServicio::where('ruta_id', $this->ruta_id)->where('status_ruta_servicios', 2)->count();

        //revisar si ya trae los envases
        $entregas =  RutaServicio::where('ruta_id', $this->ruta_id)->where('tipo_servicio', 1)->count();;
        $envases = RutaServicio::where('ruta_id', $this->ruta_id)->where('status_ruta_servicios', 2)
            ->where('tipo_servicio', 1)->count();

        if ($entregas == $envases) {
            if ($serviciosRutaAll == $servicioRutastatus2) {
                $ruta = Ruta::findOrFail($this->ruta_id);
                $ruta->ctg_rutas_estado_id = 3;
                $ruta->save();
                $this->ruta_id = 0;
                $this->dispatch('success-fin-ruta');
            } else {
                $this->dispatch('error-fin-ruta');
            }
        } else {
            $this->dispatch('error-envases');
        }
    }
    public function Nocargar()
    {
        $this->validate([
            'idserviorutacancelado' => 'required',
            'motivoNo' => 'required',
        ], [
            'idserviorutacancelado.required' => 'id de servicio no encontrado.',
            'motivoNo.required' => 'Ingrese Motivo de Cancelación',
        ]);

        $id = $this->idserviorutacancelado;
        $servicioRuta = RutaServicio::findOrFail($id);
        $rutaId = $servicioRuta->ruta_id;

        // Crear un nuevo objeto RutaServicioReporte
        $rutaServicioReporte = new RutaServicioReporte();

        // Asignar valores del servicio actualizado al reporte
        $rutaServicioReporte->servicio_id = $servicioRuta->servicio_id;
        $rutaServicioReporte->ruta_id = $servicioRuta->ruta_id;
        $rutaServicioReporte->monto = $servicioRuta->monto;
        $rutaServicioReporte->folio = $servicioRuta->folio;
        $rutaServicioReporte->envases = $servicioRuta->envases;
        $rutaServicioReporte->tipo_servicio = $servicioRuta->tipo_servicio;
        $rutaServicioReporte->status_ruta_servicio_reportes = 5;
        $rutaServicioReporte->motivocancelacion = $this->motivoNo;

        // Guardar el nuevo registro en la base de datos
        $rutaServicioReporte->save();

        // Actualizar el modelo de Ruta relacionado
        $ruta = Ruta::findOrFail($servicioRuta->ruta_id);
        $ruta->total_ruta -= $servicioRuta->monto;
        $ruta->save();
        // Eliminar el registro de RutaServicio
        $servicioRuta->delete();
        $serviciosRutaAll = RutaServicio::where('ruta_id', $rutaId)->get();
        $servicioRutastatus2 = RutaServicio::where('ruta_id', $rutaId)->where('status_ruta_servicios', 2)->get();
        $numServicios = $serviciosRutaAll->count();
        $numServiciosStatus2 = $servicioRutastatus2->count();
        if ($numServicios == $numServiciosStatus2) {
            $ruta = Ruta::findOrFail($rutaId);
            $ruta->ctg_rutas_estado_id = 3;
            $ruta->save();
        }
    }

    public function cargarNoObtenerdatos($idservicio)
    {
        $servicioRuta = RutaServicio::find($idservicio);
        $this->idserviorutacancelado = $servicioRuta->id;
    }


    //recolecciones aceptar 
    public function cargarRecoleccion($idservicioruta, $rutaId)
    {
        $servicioRuta = RutaServicio::find($idservicioruta);
        if ($servicioRuta) {

            $servicioRuta->update(['status_ruta_servicios' => 2]);
            $this->llenarmodalservicios($servicioRuta->ruta_id); // Actualiza los datos
            // Crear un nuevo objeto RutaServicioReporte
            $rutaServicioReporte = new RutaServicioReporte();

            // Asignar valores del servicio actualizado al reporte
            $rutaServicioReporte->servicio_id = $servicioRuta->servicio_id;
            $rutaServicioReporte->ruta_id = $servicioRuta->ruta_id;
            $rutaServicioReporte->monto = $servicioRuta->monto;
            $rutaServicioReporte->folio = $servicioRuta->folio;
            $rutaServicioReporte->envases = $servicioRuta->envases;
            $rutaServicioReporte->tipo_servicio = $servicioRuta->tipo_servicio;
            $rutaServicioReporte->status_ruta_servicio_reportes = $servicioRuta->status_ruta_servicios; // Igualamos al status actualizado del servicio
            // Guardar el nuevo registro en la base de datos
            $rutaServicioReporte->save();
           
        }
    }




    public function GuardarEnvases()
    {
        // Verificar duplicados en el conjunto proporcionado
        $this->resetValidation();



        $this->validate([
            'inputs.*' => 'required|numeric',
            'sellos.*' => 'required|unique:servicios_envases_rutas,sello_seguridad',
        ], [
            'inputs.*.required' => 'La cantidad es requerida',
            'inputs.*.numeric' => 'La cantidad debe ser numérico',
            'sellos.*.required' => 'El campo de sello es requerido',
            'sellos.*.unique' => 'Este sello ya ha sido regristrado',
        ]);
        $duplicatedSellos = array_diff_assoc($this->sellos, array_unique($this->sellos));
        if (!empty($duplicatedSellos)) {
            // Marcar cada folio duplicado individualmente
            foreach ($duplicatedSellos as $index => $folio) {
                $this->addError('sellos.' . $index, 'Este sello es duplicado.');
            }
            return;
        }
        try {
            DB::beginTransaction();
            $servicioRuta = RutaServicio::find($this->idserviorutaEnvases);
            $ClienteResguardo = $servicioRuta->servicio->cliente->resguardo;
            $totalinputs = array_sum($this->inputs);
            if ($ClienteResguardo >= $servicioRuta->monto) {
                if ($servicioRuta->monto == $totalinputs) {
                    foreach ($this->inputs as $index => $input) {
                        ServicioRutaEnvases::create([
                            'ruta_servicios_id' => $this->idserviorutaEnvases,
                            'tipo_servicio' => 1,
                            'cantidad' => $this->inputs[$index],
                            'sello_seguridad' => $this->sellos[$index],
                            'folio' => $servicioRuta->folio,

                            'status_envases' => 1,
                        ]);
                    }
                    $servicioRuta->envase_cargado = 1;
                    $servicioRuta->save();
                    $this->llenarmodalservicios($servicioRuta->ruta_id);
                    $this->dispatch('successservicioEnvases', ['Los envases han sido almacenados correctamente', 'success']);
                } else {
                    $this->dispatch('successservicioEnvases', ['Las Cantidades no coinciden con el monto total de entrega', 'error']);
                }
            } else {
                $this->dispatch('successservicioEnvases', ['No cuenta con dinero en resguardo,Se notificara a las areas Correspondientes', 'error']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('successservicioEnvases', ['Ocurrio un error intenta mas tarde.', 'error']);
            // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            // Log::info('Info: ' . $e);
        }
    }

    public $papeleta;
    public function llenarmodalEnvases(RutaServicio $ServicioRuta)
    {
        $this->resetValidation();
        $this->cantidadEnvases = $ServicioRuta->envases;
        $this->idserviorutaEnvases =  $ServicioRuta->id;
        $this->papeleta =  $ServicioRuta->folio; //papeleta

       
        // Si no hay registros, inicializar los arreglos con valores vacíos
        $this->inputs = array_fill(0, $this->cantidadEnvases, '');
        $this->sellos = array_fill(0, $this->cantidadEnvases, '');
        $this->statusEnvases = 2;
        // }
    }

}
