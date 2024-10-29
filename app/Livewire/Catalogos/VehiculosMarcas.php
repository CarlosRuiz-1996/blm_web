<?php

namespace App\Livewire\Catalogos;

use Livewire\Component;
use App\Livewire\Forms\Catalogos\VehiculosForm;
use App\Models\CtgVehiculosMarca;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class VehiculosMarcas extends Component
{
    use WithPagination;
    public VehiculosForm $form;
    public $readyToLoad = false;
    public $search;
    public function loadTable()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        if ($this->readyToLoad) {
            $marcas = $this->form->getAllMarcas($this->search);
        } else {
            $marcas = [];
        }
        return view('livewire.catalogos.vehiculos-marcas', compact('marcas'));
    }

    #[On('save-marca')]
    public function save()
    {
        $this->form->store(1);
        $this->dispatch('success-marca', "El nombre de la marca se agrego al catalogo.");
    }

    public $marca_id = 0;
    public $marca;

    public function setMarca(CtgVehiculosMarca $marca)
    {
        $this->limpiar();
        $this->form->name = $marca->name;
        $this->marca_id = $marca->id;
        $this->marca = $marca;

        $this->dispatch('edit-marcas');
    }

    public function limpiar()
    {
        $this->form->name = '';
        $this->marca_id = 0;
        $this->marca = '';
    }

    #[On('update-marca')]
    public function update()
    {
        $this->form->update($this->marca, 1);
        $this->dispatch('success-marca', "El nombre de la marca se actualizo con exito.");
    }

    #[On('delete-marca')]
    public function delete(CtgVehiculosMarca $marca)
    {
        $res =  $this->form->delete($marca);
        if ($res == 1) {
            $this->dispatch('success-marca', "La marca se dio de baja correctamente.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-marca')]
    public function baja(CtgVehiculosMarca $marca)
    {
        $this->form->baja($marca, 1);
        $this->dispatch('success-marca', "La marca se dio de baja correctamente.");
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgVehiculosMarca $marca)
    {
        $this->form->reactivar($marca, 1);
        $this->dispatch('success-marca', "La marca se restauro correctamente.");
    }
}
