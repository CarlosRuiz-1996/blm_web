<?php

namespace App\Livewire\Rh;

use App\Models\Empleado;
use Livewire\Component;
use Livewire\WithPagination;

class EmpleadosInactivos extends Component
{
    use WithPagination;

    public $claveEmpleado;
    public $NombreEmpleado;
    //renderiza el componenete con los empleados con estatus 0 = incativos
    public function render()
    {
        $query = Empleado::where('status_empleado', 0); // Filtra por estatus 1

    if ($this->claveEmpleado) {
        $query->where('cve_empleado', 'like', '%' . $this->claveEmpleado . '%');
    }

    if ($this->NombreEmpleado) {
        $query->whereHas('user', function($q) {
            $q->where('name', 'like', '%' . $this->NombreEmpleado . '%');
        });
    }

    $empleados = $query->paginate(10);
        return view('livewire.rh.empleados-inactivos',compact('empleados'));
    }

    public function buscar(){
        $this->render();
    }
}
