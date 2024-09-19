<?php


namespace App\Livewire\Operaciones\Rutas;
    
use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use App\Models\RutaEmpleados;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\WithoutUrlPagination;

class AgregarOperador extends Component
{

    public RutaForm $form;
    use WithPagination,WithoutUrlPagination;
    public $selectPersonalOperador = [];
    public $readyToLoadOperador = false;


    public function mount(Ruta $ruta)
    {
        $this->form->ruta = $ruta;
    }
    #[On('render-modal-personal-Operador')]
    public function render()
    {

        if ($this->readyToLoadOperador) {
            $empleadosOperadores = $this->form->getPersonalOperador();

            $ruta_empleadosOperadores = $this->form->getRutaPersonalOperador();
        } else {
            $empleadosOperadores = [];
            $ruta_empleadosOperadores = [];
        }
        return view('livewire.operaciones.rutas.agregar-operador', compact('empleadosOperadores','ruta_empleadosOperadores'));
    }
    public function getPersonalOperador()
    {
        $this->dispatch('render-modal-personal-Operador');
        $this->readyToLoadOperador = true;
    }
    public function loadPersonalOperador()
    {
        $this->readyToLoadOperador = true;
    }
    #[On('add-personal-ruta-Operador')]
    public function AddPersonal()
    {
        $this->selectPersonalOperador = array_filter($this->selectPersonalOperador);
        if (!empty($this->selectPersonalOperador)) {

            foreach ($this->selectPersonalOperador as $empleado_id => $i) {
               
                if ($i) {
                    $this->form->storePersonal($empleado_id);
                }
            }

            $this->dispatch('success-personal-Operador', ['Equipo de seguridad agregado con exito']);
            $this->selectPersonalOperador = [];
        } else {
            $this->dispatch('error-personal-Operador', 'No hay ningun empleado seleccionado');
        }
    }


    #[On('clean-personalOperador')]
    public function clean()
    {
        $this->selectPersonalOperador = [];
        $this->resetValidation();
    }


    #[On('delete-personal-Operador')]
    public function DeletePersonal(RutaEmpleados $personal)
    {
        $this->form->deletePersonal($personal);
        $this->dispatch('success-vehiculo', ['Empleado eliminado de la ruta con exito']);
    }
}
