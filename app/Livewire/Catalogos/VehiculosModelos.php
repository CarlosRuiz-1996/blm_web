<?php

namespace App\Livewire\Catalogos;

use App\Models\CtgVehiculosModelo;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\Catalogos\VehiculosForm;

class VehiculosModelos extends Component
{

    public VehiculosForm $form;

    public function render()
    {
        $modelos = $this->form->getAllModelos();
        $marcas = $this->form->getAllMarcas();

        return view('livewire.catalogos.vehiculos-modelos',compact('modelos','marcas'));
    }

    #[On('save-modelo')]
    public function save()
    {
        $this->form->store(2);
        $this->dispatch('success-modelo', "El nombre del modelo se agrego al catalogo.");
    }

    public $modelo_id = 0;
    public $modelo;

    public function setModelo(CtgVehiculosModelo $modelo)
    {
        $this->limpiar();
        $this->form->name = $modelo->name;
        $this->form->ctg_vehiculo_marca_id = $modelo->ctg_vehiculo_marca_id;
        $this->modelo_id = $modelo->id;
        $this->modelo = $modelo;

        $this->dispatch('edit-modelos');
    }

    public function limpiar()
    {
        $this->form->name = '';
        $this->modelo_id = 0;
        $this->modelo = '';
        $this->dispatch('datatable');
    }

    #[On('update-modelo')]
    public function update()
    {
        $this->form->update($this->modelo,2);
        $this->dispatch('success-modelo', "El nombre de la modelo se actualizo con exito.");
    }

    #[On('delete-modelo')]
    public function delete(CtgVehiculosModelo $modelo)
    {
        $res =  $this->form->delete($modelo);
        if ($res == 1) {
            $this->dispatch('success-modelo', "La modelo se dio de baja correctamente.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-modelo')]
    public function baja(CtgVehiculosModelo $modelo)
    {
        $this->form->baja($modelo,2);
        $this->dispatch('success-modelo', "La modelo se dio de baja correctamente.");
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgVehiculosModelo $modelo)
    {
        $this->form->reactivar($modelo,2);
        $this->dispatch('success-modelo', "La modelo se restauro correctamente.");
    }
}
