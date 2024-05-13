<?php

namespace App\Livewire\Operaciones\Rutas;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use App\Models\RutaEmpleados;
use Livewire\WithPagination;
use Livewire\Attributes\On;
class AgregarCajero extends Component
{

    public RutaForm $form;
    use WithPagination;
    public $selectPersonalCajero = [];
    public $readyToLoadCajero = false;


    public function mount(Ruta $ruta)
    {
        $this->form->ruta = $ruta;
    }
    #[On('render-modal-personal-cajero')]
    public function render()
    {

        if ($this->readyToLoadCajero) {
            $empleadosCajeros = $this->form->getPersonalCajero();

            $ruta_empleadosCajeros = $this->form->getRutaPersonalCajero();
        } else {
            $empleadosCajeros = [];
            $ruta_empleadosCajeros = [];
        }
        return view('livewire.operaciones.rutas.agregar-cajero', compact('empleadosCajeros','ruta_empleadosCajeros'));
    }
    public function getPersonalCajero()
    {
        $this->dispatch('render-modal-personal-cajero');
        $this->readyToLoadCajero = true;
    }
    public function loadPersonalCajero()
    {
        $this->readyToLoadCajero = true;
    }
    #[On('add-personal-ruta-cajero')]
    public function AddPersonal()
    {
        $this->selectPersonalCajero = array_filter($this->selectPersonalCajero);
        if (!empty($this->selectPersonalCajero)) {

            foreach ($this->selectPersonalCajero as $empleado_id => $i) {
               
                if ($i) {
                    $this->form->storePersonal($empleado_id);
                }
            }

            $this->dispatch('success-personal-cajero', ['Equipo de seguridad agregado con exito']);
            $this->selectPersonalCajero = [];
        } else {
            $this->dispatch('error-personal-cajero', 'No hay ningun empleado seleccionado');
        }
    }


    #[On('clean-personalCajero')]
    public function clean()
    {
        $this->selectPersonalCajero = [];
        $this->resetValidation();
    }


    #[On('delete-personal-cajero')]
    public function DeletePersonal(RutaEmpleados $personal)
    {
        $this->form->deletePersonal($personal);
        $this->dispatch('success-vehiculo', ['Empleado eliminado de la ruta con exito']);
    }
}
