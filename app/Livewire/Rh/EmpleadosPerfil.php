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

    public function activarempleado($id)
    {
        // Busca al empleado por ID y actualiza su estado a activo (1)
        $empleado = Empleado::find($id);
        if ($empleado) {
            $empleado->status_empleado = 1; // Activo
            $empleado->save();

            // Opcional: Emitir un evento para refrescar la lista o mostrar un mensaje
            session()->flash('message', 'Empleado activado con éxito.');
        }
    }

    public function desactivarempleado($id)
    {
        // Busca al empleado por ID y actualiza su estado a inactivo (0)
        $empleado = Empleado::find($id);
        if ($empleado) {
            $empleado->status_empleado = 0; // Inactivo
            $empleado->save();

            // Opcional: Emitir un evento para refrescar la lista o mostrar un mensaje
            session()->flash('message', 'Empleado desactivado con éxito.');
        }
    }

    public function render()
    {
        // Buscar al empleado utilizando el ID
        $empleado = Empleado::find($this->id);

        // Pasar el empleado a la vista
        return view('livewire.rh.empleados-perfil', ['empleado' => $empleado]);
    }
}
