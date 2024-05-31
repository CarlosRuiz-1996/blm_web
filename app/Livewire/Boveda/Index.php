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
    public $idserviorutacancelado;

    public function render()
    {
        $resguardototal = Cliente::where('status_cliente',1)->sum('resguardo');
        $Movimientos =  RutaServicioReporte::paginate(10);
        $servicios = Ruta::where('ctg_rutas_estado_id', 2)->paginate(10);
        return view('livewire.boveda.index', compact('servicios','Movimientos','resguardototal'));
    }

    public function llenarmodalservicios($idruta)
    {
        $this->serviciosRuta = RutaServicio::where('ruta_id', $idruta)->get();
    }
    public function cargar($idservicioruta,$rutaId)
    {
        $servicioRuta = RutaServicio::find($idservicioruta);
        $serviciosRutaAll = RutaServicio::where('ruta_id', $rutaId)->get();
        if ($servicioRuta) {
           
            $ClienteResguardo=$servicioRuta->servicio->cliente->resguardo;
            if($ClienteResguardo>=$servicioRuta->monto){
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
            ResguardoResporte::create([
                'servicio_id' => $servicioRuta->servicio_id,
                'resguardo_actual' => $cliente = $servicioRuta->servicio->cliente->resguardo,
                'cantidad' => $servicioRuta->monto,
                'tipo_servicio' => 1,
                'status_reporte_resguardo' => 1,
            ]);
            $cliente = $servicioRuta->servicio->cliente;
            // Modificar la propiedad 'resguardo'
            $cliente->resguardo = $cliente->resguardo-$servicioRuta->monto;  // Cambia 'NuevoValor' al valor que desees
            // Guardar los cambios en la base de datos
            $cliente->save();
            
            // Cuenta el número de registros obtenidos
            $numServicios = $serviciosRutaAll->count();
            $numServiciosStatus2 = $servicioRutastatus2->count();

            if ($numServicios == $numServiciosStatus2) {
                $ruta = Ruta::findOrFail($rutaId); 
                $ruta->ctg_rutas_estado_id=3;
                $ruta->save();
            }
        }else{
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
                        $users = Empleado::whereIn('ctg_area_id', [19,2])->get();
                        NotificationsNotification::send($users, new \App\Notifications\newNotification($msg));
            $this->dispatch('ServicioResguardo', ['No cuenta con dinero en resguardo,Se notificara a las areas Correspondientes', 'error']);
        }
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
        $rutaId =$servicioRuta->ruta_id;

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
            $ruta->ctg_rutas_estado_id=3;
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
                $ruta->ctg_rutas_estado_id=3;
                $ruta->save();
            }
        }
    }
}
