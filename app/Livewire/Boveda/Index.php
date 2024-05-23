<?php

namespace App\Livewire\Boveda;

use App\Livewire\Operaciones\RutaGestion;
use App\Models\Cliente;
use App\Models\Ruta;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\Servicios;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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
    public function cargar($idservicio, $rutaId)
    {
        $servicioRuta = RutaServicio::where('servicio_id', $idservicio)->where('ruta_id', $rutaId)->first();
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
        $rutaServicioReporte->status_ruta_servicio_reportes = 5; // Igualamos al status actualizado del servicio
        $rutaServicioReporte->motivocancelacion = $this->motivoNo;

        // Guardar el nuevo registro en la base de datos
        $rutaServicioReporte->save();

        // Actualizar el modelo de Ruta relacionado
        $ruta = Ruta::findOrFail($servicioRuta->ruta_id);
        $ruta->total_ruta -= $servicioRuta->monto;
        $ruta->save();
        $servicio = Servicios::findOrFail($servicioRuta->servicio_id);
        $servicio->status_servicio = 3;
        $servicio->save();

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

    public function cargarNoObtenerdatos($idservicio, $rutaId)
    {
        $servicioRuta = RutaServicio::where('servicio_id', $idservicio)->where('ruta_id', $rutaId)->first();
        $this->idserviorutacancelado = $servicioRuta->id;
    }
}
