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
use Livewire\WithFileUploads;

class RutasYservicios extends Component
{


    use WithFileUploads;
    public $readyToLoadModal = false;
    public $evidencia_foto = [];
    protected $listeners = ['modalCerrado'];
    public $showModal = false;
    public $serviciosRutasevidencias = [];
    public $editIndex;
    public $isLoading = true;

    //escuchar eventos en tiempo real  sobre las notificaciones para renderizar el componenete livewire
    public function getListeners()
    {
        $empleado_id = Auth::user()->empleado->id;
        return [
            "echo-notification:App.Models.Empleado.{$empleado_id},notification" => 'render',
        ];
    }

    //obtenener servicios rutas en moodal
    public function rutamonto($rutaServiciosId)
    {
        $this->isLoading = true;
        // Obtener los servicios como colección de modelos Eloquent
        $this->serviciosRutasevidencias = ServicioRutaEnvases::with(['rutaServicios', 'evidencia_recolecta', 'evidencia_entrega']) // Cargar todas las relaciones necesarias
            ->where('ruta_servicios_id', $rutaServiciosId)
            ->where('status_envases', 1)
            ->get()
            ->toArray(); // Convertir a array si es necesario
        $this->editIndex = null; // Resetear el índice de edición
        $this->isLoading = false;
    }

    //obtener id del servicio a editar
    public function editService($index)
    {
        $this->editIndex = $index; // Establecer el índice del servicio que estamos editando
    }
   //actualizar  ruta_servicio
    public function updateService()
    {
        $rutaServiciosId = null;

        foreach ($this->serviciosRutasevidencias as $key => $servicio) {
            // Validar cada servicio
            $this->validate([
                "serviciosRutasevidencias.$key.cantidad" => 'required|numeric',
                "serviciosRutasevidencias.$key.evidencianueva" => 'nullable|image|max:102400', // Validar que sea una imagen si se carga
            ]);

            // Actualizar el servicio existente
            $servicioModelo = ServicioRutaEnvases::find($servicio['id']); // Busca el modelo original usando el ID

            // Si hay una nueva evidencia, guarda la imagen
            if (isset($servicio['evidencianueva'])) {
                // Construir el nombre de la imagen según el tipo de servicio
                $tipoServicio = $servicioModelo->tipo_servicio; 
                $rutaServicioId = $servicioModelo->ruta_servicios_id;
                // Define el nombre del archivo según el tipo de servicio
                $evidenciaId = $tipoServicio == "2" ? $servicioModelo->evidencia_recolecta->id : $servicioModelo->evidencia_entrega->id; // Cambia aquí

                $nombreArchivo = "Servicio_{$rutaServicioId}_" . ($tipoServicio == "2" ? "recolecta" : "entrega") . "_{$evidenciaId}_evidencia.png";
                // Guardar la imagen en la carpeta correspondiente
                $path = $servicio['evidencianueva']->storeAs(path: 'evidencias/EntregasRecolectas/', name: $nombreArchivo);
            }
            // Actualizar la cantidad directamente en el modelo
            $servicioModelo->cantidad = $servicio['cantidad'];
            $servicioModelo->save(); // Guardar los cambios

            // Asignar el ID de ruta_servicios una vez
            if ($rutaServiciosId === null) {
                $rutaServiciosId = $servicioModelo->ruta_servicios_id; // Obtener el ID de la relación
            }
        }

        // Calcular la suma total de las cantidades después de actualizar los servicios
        $totalCantidad = ServicioRutaEnvases::where('ruta_servicios_id', $rutaServiciosId)->sum('cantidad');

        // Actualizar el monto en ruta_servicios
        RutaServicio::where('id', $rutaServiciosId)->update(['monto' => $totalCantidad]);

        // Opcional: restablecer los valores
        $this->editIndex = null; // Resetear el índice de edición
        $this->serviciosRutasevidencias = [];
        $this->rutamonto($rutaServiciosId);
    }

 
   //renderiza el componente con los servicios de las rutas del dia
    public function render()
    {
        $dia = $this->obtenerDia();
        $rutaEmpleados = Ruta::where('ctg_ruta_dia_id', $dia)
            ->whereIn('ctg_rutas_estado_id', [3, 4])
            ->get();
        //dd($rutaEmpleados);
        return view('livewire.tablero.rutas-yservicios', compact('rutaEmpleados'));
    }


    //obtiene el dia de la semana para comprar en el render
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

    //obtener evidencias de entrega del servicio seleccionado
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

     //obtener evidencias de recolecta del servicio seleccionado
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

     //obtener evidencias de compras del servicio seleccionado
    public function evidenciaCompra(DetallesCompraEfectivo $detalle)
    {
        $detalles = $detalle;
        $detallesencva = $detalles->envase;
        $this->readyToLoadModal = false;
        $this->evidencia_foto = [];
        $this->evidencia_foto[] =  'evidencias/CompraEfectivo/compra_efectivo_detalle_' . $detalle->envase->id . '.png';
        $this->readyToLoadModal = true;
    }


    //escucha el evento de cerrae modal para resetear valores
    #[On('modalCerrado')]
    public function modalCerrado()
    {
        $this->isLoading = true;
        // Lógica que quieres ejecutar cuando el modal se cierre
        $this->evidencia_foto = [];
        $this->readyToLoadModal = false;
    }
}
