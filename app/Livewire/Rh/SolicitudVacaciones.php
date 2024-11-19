<?php

namespace App\Livewire\Rh;

use App\Models\CtgMotivoVacaciones;
use App\Models\Empleado;
use App\Models\SolicitudVacacion;
use Livewire\Component;
use Livewire\WithPagination;

class SolicitudVacaciones extends Component
{
    use WithPagination;

    public $empleado_id_filtro;
    public $fecha_inicio_filtro;
    public $fecha_fin_filtro;
    public $status_vacaciones_filtro;
    public $fecha_inicio;
    public $fecha_fin;
    public $status_vacaciones;
    public $isOpen=false;
    public $empleados;
    public $motivos;
    public $ctg_motivo_vacaciones_id;
    public $solicitudSeleccionada;
    public $solicitudId; 
    public $empleado_id;
    

   //incializa el componente
    public function mount(){
        $this->empleados = Empleado::all(); // Carga los empleados
        $this->motivos = CtgMotivoVacaciones::all(); // Carga los motivos de vacaciones
    }

    //rendeeriza el componenete paginado  validando filtros
    public function render()
    {
        $this->empleados = Empleado::all(); // Carga los empleados
        $this->motivos = CtgMotivoVacaciones::all(); // Carga los motivos de vacaciones
        $solicitudes = SolicitudVacacion::query();

        if ($this->empleado_id_filtro) {
            $solicitudes->where('empleado_id', $this->empleado_id_filtro);
        }

        if ($this->fecha_inicio_filtro) {
            $solicitudes->where('fecha_inicio', '>=', $this->fecha_inicio_filtro);
        }

        if ($this->fecha_fin_filtro) {
            $solicitudes->where('fecha_fin', '<=', $this->fecha_fin_filtro);
        }

        if ($this->status_vacaciones_filtro) {
            $solicitudes->where('status_vacaciones', $this->status_vacaciones_filtro);
        }

        $solicitudes = $solicitudes->paginate(10);

        return view('livewire.rh.solicitud-vacaciones', compact('solicitudes'));
    }

    //resetea filtros
    public function resetFilters()
    {
        $this->reset(['empleado_id_filtro', 'fecha_inicio_filtro', 'fecha_fin_filtro', 'status_vacaciones_filtro']);
    }    

    protected $rules = [
        'empleado_id' => 'required|exists:empleados,id', // Asegúrate de que la tabla empleados existe
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after:fecha_inicio',
        'ctg_motivo_vacaciones_id' => 'required|exists:ctg_motivo_vacaciones,id', // Cambia según tu tabla
    ];


    //guarda la solicitud de vacaciones edicion o nueva
    public function submit()
    {
        // Validar y guardar la solicitud
        $this->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'ctg_motivo_vacaciones_id' => 'required|exists:ctg_motivo_vacaciones,id',
        ]);

        if ($this->solicitudId) {
            // Editar solicitud existente
            $solicitud = SolicitudVacacion::find($this->solicitudId);
            $solicitud->update([
                'empleado_id' => $this->empleado_id,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'ctg_motivo_vacaciones_id' => $this->ctg_motivo_vacaciones_id,
            ]);

            session()->flash('message', 'Solicitud editada exitosamente.');
        } else {
            // Crear nueva solicitud
            SolicitudVacacion::create([
                'empleado_id' => $this->empleado_id,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'status_vacaciones' =>3,
                'ctg_motivo_vacaciones_id' => $this->ctg_motivo_vacaciones_id,
            ]);

            session()->flash('message', 'Solicitud enviada exitosamente.');
        }

        $this->reset(); // Restablecer campos
        $this->solicitudId=null;
        $this->closeModal();
    }


    //abre modal si es que es necesario editar
    public function openModal($solicitudId = null)
    {
        $this->isOpen = true;
        $this->solicitudId = $solicitudId;

        if ($solicitudId) {
            // Cargar datos de la solicitud para editar
            $solicitud = SolicitudVacacion::find($solicitudId);
            $this->empleado_id = $solicitud->empleado_id;
            $this->fecha_inicio = \Carbon\Carbon::parse($solicitud->fecha_inicio)->format('Y-m-d');
            $this->fecha_fin = \Carbon\Carbon::parse($solicitud->fecha_fin)->format('Y-m-d');
            $this->ctg_motivo_vacaciones_id = $solicitud->ctg_motivo_vacaciones_id;
        } else {
            // Resetear los campos si es una nueva solicitud
            $this->solicitudId=null;
        }
    }
    //cierra modal
    public function closeModal()
    {
        $this->isOpen = false;
    }
 
     // Función para eliminar una solicitud
     public function eliminar($solicitudId)
     {
         $solicitud = SolicitudVacacion::find($solicitudId);
         if ($solicitud) {
             $solicitud->delete();
             session()->flash('message', 'Solicitud eliminada exitosamente.');
         }
     }
 
     // Función para aceptar una solicitud (cambiar el estado a "Aprobada")
     public function aceptarsoli($solicitudId)
     {
         $solicitud = SolicitudVacacion::find($solicitudId);
         if ($solicitud && $solicitud->status_vacaciones == 2 || $solicitud->status_vacaciones == 3) {
             $solicitud->status_vacaciones = 1; // Cambiar el estado a "Aprobada"
             $solicitud->save();
             session()->flash('message', 'Solicitud aceptada exitosamente.');
         }
     }
}

