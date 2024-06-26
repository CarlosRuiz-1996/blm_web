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
    public $folios = [];
    public $originalFolios = [];
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
        if ($servicioRuta) {

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
        }
    }

    public function finailzar()
    {

        $serviciosRutaAll = RutaServicio::where('ruta_id', $this->ruta_id)->count();
        $servicioRutastatus2 = RutaServicio::where('ruta_id', $this->ruta_id)->where('status_ruta_servicios', 2)->count();

        if ($serviciosRutaAll == $servicioRutastatus2) {
            $ruta = Ruta::findOrFail($this->ruta_id);
            $ruta->ctg_rutas_estado_id = 3;
            $ruta->save();
            $this->ruta_id = 0;
            $this->dispatch('success-fin-ruta');
        } else {
            $this->dispatch('error-fin-ruta');
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
        $serviciosRutaAll = RutaServicio::where('ruta_id', $rutaId)->get();
        if ($servicioRuta) {

            $servicioRuta->update(['status_ruta_servicios' => 2]);
            $this->llenarmodalservicios($servicioRuta->ruta_id); // Actualiza los datos
            $servicioRutastatus2 = RutaServicio::where('ruta_id', $rutaId)->where('status_ruta_servicios', 2)->get();
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
            // Cuenta el número de registros obtenidos
            $numServicios = $serviciosRutaAll->count();
            $numServiciosStatus2 = $servicioRutastatus2->count();

            if ($numServicios == $numServiciosStatus2) {
                $ruta = Ruta::findOrFail($rutaId);
                $ruta->ctg_rutas_estado_id = 3;
                $ruta->save();
            }
        }
    }




    public function GuardarEnvases()
    {
        // Verificar duplicados en el conjunto proporcionado
        $this->resetValidation();



        $this->validate([
            'inputs.*' => 'required|numeric',
            'folios.*' => 'required|unique:servicios_envases_rutas,folio',
        ], [
            'inputs.*.required' => 'La cantidad es requerida',
            'inputs.*.numeric' => 'La cantidad debe ser numérico',
            'folios.*.required' => 'El campo de folios es requerido',
            'folios.*.unique' => 'Este folio ya ha sido regristrado',
        ]);
        $duplicatedFolios = array_diff_assoc($this->folios, array_unique($this->folios));
        if (!empty($duplicatedFolios)) {
            // Marcar cada folio duplicado individualmente
            foreach ($duplicatedFolios as $index => $folio) {
                $this->addError('folios.' . $index, 'Este folio es duplicado.');
            }
            return;
        }
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
                        'folio' => $this->folios[$index],
                        'status_envases' => 1,
                    ]);
                    $this->dispatch('successservicioEnvases', ['Los envases han sido almacenados correctamente', 'success']);
                }
            } else {
                $this->dispatch('successservicioEnvases', ['Las Cantidades no coinciden con el monto total de entrega', 'error']);
            }
        } else {
            $this->dispatch('successservicioEnvases', ['No cuenta con dinero en resguardo,Se notificara a las areas Correspondientes', 'error']);
        }
    }

    public $papeleta;
    public function llenarmodalEnvases(RutaServicio $ServicioRuta)
    {
        $this->resetValidation();
        $this->cantidadEnvases = $ServicioRuta->envases;
        $this->idserviorutaEnvases =  $ServicioRuta->id;
        $this->papeleta =  $ServicioRuta->folio;//papeleta

        // Consultar los registros de servicios_envases_rutas para esta ruta
        // $serviciosEnvases = ServicioRutaEnvases::where('ruta_servicios_id', $ServicioRuta->id)->get();

        // Si hay registros, llenar los arreglos con los valores recuperados
        // if ($serviciosEnvases->isNotEmpty()) {
        //     $this->inputs = $serviciosEnvases->pluck('cantidad')->toArray();
        //     $this->folios = $serviciosEnvases->pluck('folio')->toArray();
        //     $this->originalFolios = $serviciosEnvases->pluck('folio')->toArray();
        //     $this->statusEnvases = 1;
        // } else {
        // Si no hay registros, inicializar los arreglos con valores vacíos
        $this->inputs = array_fill(0, $this->cantidadEnvases, '');
        $this->folios = array_fill(0, $this->cantidadEnvases, '');
        $this->originalFolios = array_fill(0, $this->cantidadEnvases, '');
        $this->statusEnvases = 2;
        // }
    }

    public function EditarEnvases()
    {
        // Verificar duplicados en el conjunto proporcionado
        $this->resetValidation();
        $duplicatedFolios = array_diff_assoc($this->folios, array_unique($this->folios));
        if (!empty($duplicatedFolios)) {
            // Marcar cada folio duplicado individualmente
            foreach ($duplicatedFolios as $index => $folio) {
                $this->addError('folios.' . $index, 'Este folio es duplicado.');
            }
            return;
        }

        // Validar datos de entrada
        $this->validate([
            'inputs.*' => 'required|numeric',
            'folios.*' => 'required',
        ], [
            'inputs.*.required' => 'La cantidad es requerida',
            'inputs.*.numeric' => 'La cantidad debe ser numérica',
            'folios.*.required' => 'El campo de folios es requerido',
        ]);
        $servicioRuta = RutaServicio::find($this->idserviorutaEnvases);
        $ClienteResguardo = $servicioRuta->servicio->cliente->resguardo;
        $totalinputs = array_sum($this->inputs);
        if ($ClienteResguardo >= $servicioRuta->monto) {
            if ($servicioRuta->monto == $totalinputs) {

                foreach ($this->inputs as $index => $input) {
                    // Buscar el registro existente por su índice y ruta_servicios_id utilizando el folio original
                    $registro = ServicioRutaEnvases::where('ruta_servicios_id', $this->idserviorutaEnvases)
                        ->where('folio', $this->originalFolios[$index])
                        ->first();

                    // Si se encontró el registro, actualizar sus valores
                    if ($registro) {
                        $registro->cantidad = $input;

                        // Actualizar el folio solo si es diferente
                        if ($registro->folio != $this->folios[$index]) {
                            // Validar unicidad solo si el folio ha cambiado
                            $this->validate([
                                'folios.' . $index => 'required|unique:servicios_envases_rutas,folio',
                            ], [
                                'folios.' . $index . '.unique' => 'El folio ya está siendo utilizado por otro registro',
                            ]);

                            // Si pasa la validación, actualizar el folio
                            $registro->folio = $this->folios[$index];
                        }

                        // Guardar el registro actualizado
                        $registro->save();
                    }
                }
                $this->dispatch('successservicioEnvases', ['Los envases han sido editados correctamente', 'success']);
            } else {
                $this->dispatch('successservicioEnvases', ['Las Cantidades no coinciden con el monto total de entrega', 'error']);
            }
        } else {
            $this->dispatch('successservicioEnvases', ['No cuenta con dinero en resguardo', 'error']);
        }

        $this->llenarmodalEnvases($this->idserviorutaEnvases, 0);
    }
}
