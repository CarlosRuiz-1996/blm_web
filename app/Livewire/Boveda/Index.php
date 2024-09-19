<?php

namespace App\Livewire\Boveda;

use App\Livewire\Operaciones\RutaGestion;
use App\Models\Cliente;
use App\Models\CompraEfectivo;
use App\Models\Empleado;
use App\Models\Notification;
use App\Models\Reprogramacion;
use App\Models\ResguardoResporte;
use App\Models\Ruta;
use App\Models\RutaCompraEfectivo;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\ServicioRutaEnvases;
use App\Models\Servicios;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationsNotification;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $serviciosRuta;
    public $motivoNo;
    public $idservioruta;
    public $idserviorutaEnvases;
    public $idserviorutacancelado;
    public $statusEnvases;
    public $inputs = [];
    public $sellos = [];
    public $readyToLoad = false;

    public function render()
    {
        if ($this->readyToLoad) {
            $resguardototal = Cliente::where('status_cliente', 1)->sum('resguardo');
            $Movimientos =  RutaServicioReporte::paginate(10);
            $servicios = Ruta::where('ctg_rutas_estado_id', 2)->paginate(10);
        } else {
            $resguardototal = 0;
            $Movimientos =  [];
            $servicios = [];
        }

        return view('livewire.boveda.index', compact('servicios', 'Movimientos', 'resguardototal'));
    }
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
    public $ruta_id;
    public $compra_efectivo;

    public function llenarmodalservicios($idruta)
    {
        $this->ruta_id = $idruta;
        $this->serviciosRuta = RutaServicio::where('ruta_id', $idruta)->where('status_ruta_servicios','!=',6)->get();
        //compra de efectivo
        $this->compra_efectivo = RutaCompraEfectivo::where('ruta_id', $idruta)->where('status_ruta_compra_efectivos', '<', 3)->get()
            ?: collect();
    }

    public $compra_detalle = [];
    public $readyToLoadModal = false;

    public function showCompraDetail(CompraEfectivo $compra)
    {
        $this->compra_detalle = $compra;
        $this->readyToLoadModal = true;
    }
    public function cargar($idservicioruta)
    {
        $servicioRuta = RutaServicio::find($idservicioruta);
        try {
            DB::beginTransaction();

            if ($servicioRuta->envase_cargado == 0) {

                throw new \Exception('Aun no cuentas con evases...');
            }

            $ClienteResguardo = $servicioRuta->servicio->cliente->resguardo;
            if ($ClienteResguardo >= $servicioRuta->monto) {
                $servicioRuta->update(['status_ruta_servicios' => 4]);
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
                $rutaServicioReporte->ruta_servicio_id = $servicioRuta->id;

                $rutaServicioReporte->area = 3;

                // Guardar el nuevo registro en la base de datos
                $rutaServicioReporte->save();
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
                $this->dispatch('successservicioEnvases', ['No cuenta con dinero en resguardo,Se notificara a las areas Correspondientes', 'error']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('successservicioEnvases', [$e->getMessage() ?? 'Ocurrio un error intenta mas tarde.', 'error']);
        }
    }

    // #[On('finalizar-ruta')]
    public function finailzar()
    {

        $compra_efectivo = RutaCompraEfectivo::where('ruta_id', $this->ruta_id)
        ->where('status_ruta_compra_efectivos', 1)->count();

        $serviciosRutaAll = RutaServicio::where('ruta_id', $this->ruta_id)->where('status_ruta_servicios','!=',3)->count();
       
        // $today = Carbon::today();
        $servicioRutastatus2 = RutaServicio::where('ruta_id', $this->ruta_id)
            ->where(function ($query)  {
            $query->where('status_ruta_servicios', 4)
                ->orWhere(function ($query){
                    $query->where('status_ruta_servicios', 0);
                        // ->whereDate('updated_at', $today)
                })
                ;
        })->count();
        //revisar si ya trae los envases y no contempla las de reprogramacion
        $entregas =  RutaServicio::where('ruta_id', $this->ruta_id)->where('tipo_servicio', 1)
            ->where(function ($query) {
                $query->where('status_ruta_servicios', 1)
                    ->orWhere('status_ruta_servicios', 4);
            })
            ->count();
        $envases = RutaServicio::where('ruta_id', $this->ruta_id)->where('status_ruta_servicios', 4)
            ->where('tipo_servicio', 1)->count();

        if ($entregas == $envases) {
            if ($serviciosRutaAll == $servicioRutastatus2) {

                if ($compra_efectivo == 0) {
                    $ruta = Ruta::findOrFail($this->ruta_id);
                    $ruta->ctg_rutas_estado_id = 3;
                    $ruta->save();
                    $this->ruta_id = 0;
                    $this->dispatch('success-fin-ruta');
                } else {
                    $this->dispatch('error-fin-ruta-compra');
                }
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
        try {
            DB::beginTransaction();
            $id = $this->idserviorutacancelado;
            $servicioRuta = RutaServicio::findOrFail($id);
            // $rutaId = $servicioRuta->ruta_id;

            // Crear un nuevo objeto RutaServicioReporte
            $rutaServicioReporte = new RutaServicioReporte();

            // Asignar valores del servicio actualizado al reporte
            $rutaServicioReporte->servicio_id = $servicioRuta->servicio_id;
            $rutaServicioReporte->ruta_id = $servicioRuta->ruta_id;
            $rutaServicioReporte->monto = $servicioRuta->monto;
            $rutaServicioReporte->folio = $servicioRuta->folio;
            $rutaServicioReporte->envases = $servicioRuta->envases;
            $rutaServicioReporte->tipo_servicio = $servicioRuta->tipo_servicio;
            $rutaServicioReporte->status_ruta_servicio_reportes = 0;
            $rutaServicioReporte->ruta_servicio_id = $servicioRuta->id;
            $rutaServicioReporte->area = 3;
            // Guardar el nuevo registro en la base de datos
            $rutaServicioReporte->save();

            //generar reprogramacion
            Reprogramacion::create([
                'motivo' => $this->motivoNo,
                'ruta_servicio_id' => $servicioRuta->id,
                'area_id' => 3
            ]);

            // Actualizar el modelo de Ruta relacionado
            $ruta = Ruta::findOrFail($servicioRuta->ruta_id);
            $ruta->total_ruta -= $servicioRuta->monto;
            $ruta->save();
            // Eliminar el registro de RutaServicio
            $servicioRuta->status_ruta_servicios = 0;
            $servicioRuta->save();

            $this->dispatch('successservicioEnvases', ['Servicio mandado a reprogramación', 'success']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('successservicioEnvases', ['Ocurrio un error intenta mas tarde.', 'error']);
            // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            // Log::info('Info: ' . $e);
        }
    }

    public function cargarNoObtenerdatos($idservicio)
    {
        $servicioRuta = RutaServicio::find($idservicio);
        $this->idserviorutacancelado = $servicioRuta->id;
    }


    //recolecciones aceptar 
    public function cargarRecoleccion($idservicioruta)
    {
        $servicioRuta = RutaServicio::find($idservicioruta);
        if ($servicioRuta) {
            try {
                DB::beginTransaction();
                $servicioRuta->update(['status_ruta_servicios' => 4]);
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
                $rutaServicioReporte->ruta_servicio_id = $servicioRuta->id;
                $rutaServicioReporte->area = 3;

                // Guardar el nuevo registro en la base de datos
                $rutaServicioReporte->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('successservicioEnvases', ['Ocurrio un error intenta mas tarde.', 'error']);

                // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
                // Log::info('Info: ' . $e);
            }
        } else {
            $this->dispatch('successservicioEnvases', ['Ocurrio un error intenta mas tarde.', 'error']);
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

            // dd($servicioRuta->servicio->cliente);
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
                    $this->dispatch('error', ['Las Cantidades no coinciden con el monto total de entrega.']);
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
    public $MontoRecolecta;
    public $envasescantidad;

    public function llenarmodalEnvases(RutaServicio $ServicioRuta)
    {

        $this->resetValidation();
        $this->idserviorutaEnvases =  $ServicioRuta->id;
        $this->papeleta =  $ServicioRuta->folio; //papeleta
        $this->MontoRecolecta = $ServicioRuta->monto;
        $this->envasescantidad = $ServicioRuta->envases;
    }

    public function envase_recolecta()
    {
        $this->validate([
            'envasescantidad' => 'required',
        ], [
            'envasescantidad.required' => 'La cantidad de envases es obligatoria',
        ]);

        $this->inputs = array_fill(0, $this->envasescantidad, '');
        $this->sellos = array_fill(0, $this->envasescantidad, '');
        $this->statusEnvases = 2;
    }

    public function limpiarDatos()
    {
        $this->reset('readyToLoadModal', 'compra_detalle');
    }

    #[On('confirmCompra-boveda')]
    public function confirmCompra(CompraEfectivo $compra, $op)
    {

        try {
            DB::beginTransaction();
            $compra->ruta_compra->status_ruta_compra_efectivos = $op;
            $compra->ruta_compra->save();

            if ($op == 0) {
                $compra->status_compra_efectivos = 1;
                $compra->save();
            }
            $this->llenarmodalservicios($compra->ruta_compra->ruta_id);
            $conf = $op != 0 ? 'acepto.' : 'rechazo';
            $msg = 'La compra se ' . $conf . ' con exito.';
            $this->dispatch('success-compra', $msg);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('error', ['Hubo un error, intenta mas tarde.']);
        }
    }
}
