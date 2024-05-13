<?php

namespace App\Livewire\Rh;

use App\Models\SolicitudVacacion;
use Livewire\Component;
use Livewire\WithPagination;

class SolicitudVacaciones extends Component
{
    use WithPagination;

    public $empleado_id;
    public $fecha_inicio;
    public $fecha_fin;
    public $status_vacaciones;

    public function render()
    {
        $solicitudes = SolicitudVacacion::query();

        if ($this->empleado_id) {
            $solicitudes->where('empleado_id', $this->empleado_id);
        }

        if ($this->fecha_inicio) {
            $solicitudes->where('fecha_inicio', '>=', $this->fecha_inicio);
        }

        if ($this->fecha_fin) {
            $solicitudes->where('fecha_fin', '<=', $this->fecha_fin);
        }

        if ($this->status_vacaciones) {
            $solicitudes->where('status_vacaciones', $this->status_vacaciones);
        }

        $solicitudes = $solicitudes->paginate(10);

        return view('livewire.rh.solicitud-vacaciones', compact('solicitudes'));
    }
    public function resetFilters()
    {
        $this->reset(['empleado_id', 'fecha_inicio', 'fecha_fin', 'status_vacaciones']);
    }
}

