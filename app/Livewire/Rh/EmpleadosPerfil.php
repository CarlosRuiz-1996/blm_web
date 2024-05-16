<?php

namespace App\Livewire\Rh;

use App\Models\Empleado;
use Livewire\Component;

class EmpleadosPerfil extends Component
{
    public $id; // Definir una propiedad para almacenar el ID del empleado

    public function mount($id)
    {
        $this->id = $id; // Asignar el ID recibido a la propiedad $id
    }

    public function render()
    {
        // Buscar al empleado utilizando el ID
        $empleado = Empleado::find($this->id);

        // Pasar el empleado a la vista
        return view('livewire.rh.empleados-perfil', ['empleado' => $empleado]);
    }
}
