<?php

namespace App\Livewire\Rh;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SolicitudVacacion;

class EmpleadosVacacionesPerfil extends Component
{
    use WithPagination; // Habilitar paginación en Livewire

    public $empleadoId; // Variable para almacenar el ID del empleado

    //inicializar el componente con el ID del empleado
    public function mount($empleadoId)
    {
        $this->empleadoId = $empleadoId;
    }

    public function render()
    {
        // Realizar la consulta con paginación para obtener las solicitudes de vacaciones del empleado
        $solicitudesVacaciones = SolicitudVacacion::where('empleado_id', $this->empleadoId)->paginate(10); // 10 solicitudes por página

        return view('livewire.rh.empleados-vacaciones-perfil', [
            'solicitudesVacaciones' => $solicitudesVacaciones,
        ]);
    }
}
