<?php

namespace App\Livewire\Tablero;

use App\Models\DetallesCompraEfectivo;
use App\Models\Ruta;
use App\Models\RutaServicio;
use App\Models\ServicioRutaEnvases;
use App\Models\Servicios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class RutasYservicios extends Component
{


    // detalles de la comrpa
    public $readyToLoadModal = false;
    public $evidencia_foto = [];
    protected $listeners = ['modalCerrado'];
    public $showModal=false;
    public $serviciosRutasevidencias = [];
    public $editIndex; // Para saber qué servicio estamos editando

    public function getListeners()
    {
        $empleado_id = Auth::user()->empleado->id;
        return [
            // Private Channel
            // "echo:notification.{$empleado_id},notification" => 'render'
            "echo-notification:App.Models.Empleado.{$empleado_id},notification" => 'render',
        ];
    }
    public function rutamonto($rutaServiciosId)
    {
        // Cargar todos los servicios relacionados con la ruta
        $this->serviciosRutasevidencias = ServicioRutaEnvases::where('ruta_servicios_id', $rutaServiciosId)->get()->toArray();
        $this->editIndex = null; // Resetear el índice de edición
    }

    public function editService($index)
    {
        // Establecer el índice del servicio que estamos editando
        $this->editIndex = $index;
    }

    public function updateService()
    {
        // Variable para almacenar el ID de ruta_servicios
        $rutaServiciosId = null;
    
        // Validar y guardar los cambios para cada servicio
        foreach ($this->serviciosRutasevidencias as $key => $servicio) {
            $this->validate([
                "serviciosRutasevidencias.$key.cantidad" => 'required|numeric',
            ]);
    
            // Actualizar el servicio
            ServicioRutaEnvases::find($servicio['id'])->update($servicio);
            
            // Obtener el ID de ruta_servicios del primer servicio (suponiendo que todos tienen el mismo)
            if ($rutaServiciosId === null) {
                $rutaServiciosId = $servicio['ruta_servicios_id']; // Asumiendo que este campo está presente
            }
        }
    
        // Calcular la suma total de las cantidades después de actualizar los servicios
        $totalCantidad = ServicioRutaEnvases::where('ruta_servicios_id', $rutaServiciosId)->sum('cantidad');
    
        // Actualizar el monto en ruta_servicios
        RutaServicio::where('id', $rutaServiciosId)->update(['monto' => $totalCantidad]);
    
        // Opcional: restablecer los valores
        $this->editIndex = null; // Resetear el índice de edición
    }
    
    

    public function render()
    {
        $dia = $this->obtenerDia();
        $rutaEmpleados = Ruta::where('ctg_ruta_dia_id', $dia)
            ->whereIn('ctg_rutas_estado_id', [3, 4])
            ->get();
        //dd($rutaEmpleados);
        return view('livewire.tablero.rutas-yservicios', compact('rutaEmpleados'));
    }

    public function obtenerservicio() {}

    public function obtenerDia()
    {
        $dayOfWeek = Carbon::now()->dayOfWeek; // Obtiene el día de la semana (0 para Domingo, 1 para Lunes, etc.)
        $id = 0;
        switch ($dayOfWeek) {
            case 1: // Lunes
                $id = 1;
                break;
            case 2: // Martes
                $id = 2;
                break;
            case 3: // Miércoles
                $id = 3;
                break;
            case 4: // Jueves
                $id = 4;
                break;
            case 5: // Viernes
                $id = 5;
                break;
            case 6: // Sábado
                $id = 6;
                break;
            case 0: // Domingo
                $id = 7;
                break;
        }
        return $id;
    }


    public function evidenciaEntrega($id)
    {
        $this->readyToLoadModal = false;
        $this->evidencia_foto = [];
        $servicio_envases = ServicioRutaEnvases::where('ruta_servicios_id', $id)
            ->where('status_envases', 1)
            ->get();

        // Asegúrate de que evidencia_foto sea un array para almacenar múltiples rutas

        // Itera sobre los resultados y construye las rutas de evidencia para cada uno
        foreach ($servicio_envases as $servicio_envase) {
            $this->evidencia_foto[] = 'evidencias/EntregasRecolectas/Servicio_' . $servicio_envase->ruta_servicios_id . '_entrega_' . $servicio_envase->evidencia_entrega->id . '_evidencia.png';
        }

        $this->readyToLoadModal = true;
    }
    public function evidenciaRecolecta($id)
    {
        $this->readyToLoadModal = false;
        $this->evidencia_foto = [];
        $servicio_envases = ServicioRutaEnvases::where('ruta_servicios_id', $id)
            ->where('status_envases', 1)
            ->get();

        // Asegúrate de que evidencia_foto sea un array para almacenar múltiples rutas


        // Itera sobre los resultados y construye las rutas de evidencia para cada uno
        foreach ($servicio_envases as $servicio_envase) {
            $this->evidencia_foto[] = 'evidencias/EntregasRecolectas/Servicio_'
                . $servicio_envase->ruta_servicios_id
                . '_recolecta_'
                . $servicio_envase->evidencia_recolecta->id
                . '_evidencia.png';
        }

        $this->readyToLoadModal = true;
    }

    public function evidenciaCompra(DetallesCompraEfectivo $detalle)
    {
        $detalles = $detalle;
        $detallesencva = $detalles->envase;
        $this->readyToLoadModal = false;
        $this->evidencia_foto = [];
        $this->evidencia_foto[] =  'evidencias/CompraEfectivo/compra_efectivo_detalle_' . $detalle->envase->id . '.png';
        $this->readyToLoadModal = true;
    }

    #[On('modalCerrado')]
    public function modalCerrado()
    {
        // Lógica que quieres ejecutar cuando el modal se cierre
        $this->evidencia_foto = [];
        $this->readyToLoadModal = false;
    }
}
