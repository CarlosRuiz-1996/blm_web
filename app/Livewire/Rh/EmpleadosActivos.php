<?php

namespace App\Livewire\Rh;

use App\Models\Empleado;
use Livewire\Component;
use Livewire\WithPagination;

class EmpleadosActivos extends Component
{
    use WithPagination;

    public $claveEmpleado;
    public $NombreEmpleado;
    
    //renderiza el componenete con los empelados que esten activos 
    public function render()
    {
        $query = Empleado::where('status_empleado', 1); // Filtra por estatus 1
    //si en filtro esta claveEmpleado buscar por clave
    if ($this->claveEmpleado) {
        $query->where('cve_empleado', 'like', '%' . $this->claveEmpleado . '%');
    }
        //si en filtro esta nombre empleado buscar por empleado
    if ($this->NombreEmpleado) {
        $query->whereHas('user', function($q) {
            $q->where('name', 'like', '%' . $this->NombreEmpleado . '%');
        });
    }

    $empleados = $query->paginate(10);
        return view('livewire.rh.empleados-activos',compact('empleados'));
    }
    //realizar render cuando buscar para filtar
    public function buscar(){
        $this->render();
    }
}

