<?php

namespace App\Livewire\Operaciones\Rutas;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use App\Models\RutaEmpleados;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\WithoutUrlPagination;

class AgregarPersonal extends Component
{
    public RutaForm $form;
    use WithPagination,WithoutUrlPagination;
    public $selectPersonal = [];
    public $readyToLoad = false;


    public function mount(Ruta $ruta)
    {
        $this->form->ruta = $ruta;
    }
    #[On('render-modal-personal')]
    public function render()
    {

        if ($this->readyToLoad) {
            $empleados = $this->form->getPersonal();

            $ruta_empleados = $this->form->getRutaPersonal();
        } else {
            $empleados = [];
            $ruta_empleados = [];
        }
        return view('livewire.operaciones.rutas.agregar-personal', compact('empleados','ruta_empleados'));
    }
    public function getPersonal()
    {
        $this->dispatch('render-modal-personal');
        $this->readyToLoad = true;
    }
    public function loadPersonal()
    {
        $this->readyToLoad = true;
    }
    #[On('add-personal-ruta')]
    public function AddPersonal()
    {
        $this->selectPersonal = array_filter($this->selectPersonal);
        if (!empty($this->selectPersonal)) {

            foreach ($this->selectPersonal as $empleado_id => $i) {
               
                if ($i) {
                    $this->form->storePersonal($empleado_id);
                }
            }

            $this->dispatch('success-personal', ['Equipo de seguridad agregado con exito']);
            $this->selectPersonal = [];
        } else {
            $this->dispatch('error-personal', 'No hay ningun empleado seleccionado');
        }
    }


    #[On('clean-personal')]
    public function clean()
    {
        $this->selectPersonal = [];
        $this->resetValidation();
    }


    #[On('delete-personal')]
    public function DeletePersonal(RutaEmpleados $personal)
    {
        $this->form->deletePersonal($personal);
        $this->dispatch('success-vehiculo', ['Empleado eliminado de la ruta con exito']);
    }
}
