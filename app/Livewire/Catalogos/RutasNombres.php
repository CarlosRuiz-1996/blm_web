<?php

namespace App\Livewire\Catalogos;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\Catalogos\RutasForm;
use App\Models\CtgRutas;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class RutasNombres extends Component
{

    public RutasForm $form;
    public $readyToLoad = false;
    use WithPagination, WithoutUrlPagination;
    public $search;
    public function loadTable()
    {
        $this->readyToLoad = true;
    }
    public function render()
    {
        if ($this->readyToLoad) {

        $rutas= $this->form->getAllRutas($this->search);
    } else {
        $rutas = [];
    }
        return view('livewire.catalogos.rutas-nombres', compact('rutas'));
    }


    #[On('save-ruta')]
    public function save()
    {
        $res =$this->form->store(2);

        if ($res == 1) {
            $this->dispatch('success-ruta', "El nombre de la ruta se agrego al catalogo.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    public $ruta_id = 0;
    public $ruta;

    public function setRuta(CtgRutas $ruta)
    {
        $this->limpiar();
        $this->form->name = $ruta->name;
        $this->ruta_id = $ruta->id;
        $this->ruta = $ruta;

        $this->dispatch('edit-rutas');
    }

    public function limpiar()
    {
        $this->resetValidation();

        $this->form->name = '';
        $this->ruta_id = 0;
        $this->ruta = '';
    }

    #[On('update-ruta')]
    public function update()
    {
        $res = $this->form->update($this->ruta);
        if ($res == 1) {
            $this->dispatch('success-ruta', "El nombre de la ruta se actualizo con exito.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('delete-ruta')]
    public function delete(CtgRutas $ruta)
    {
        $res =  $this->form->delete($ruta);
        if ($res == 1) {
            $this->dispatch('success-ruta', "El nombre de la ruta se dio de baja correctamente.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-ruta')]
    public function baja(CtgRutas $ruta)
    {
        $this->form->baja($ruta,2);
        $this->dispatch('success-ruta', "El nombre de la ruta se dio de baja correctamente.");
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgRutas $ruta)
    {
        $this->form->reactivar($ruta,2);
        $this->dispatch('success-ruta', "El nombre de la ruta se restauro correctamente.");
    }
}
