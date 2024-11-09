<?php

namespace App\Livewire\Catalogos;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\Catalogos\RutasForm;
use App\Models\CtgRutasRiesgo;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class RutasRiesgos extends Component
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
            $riesgos = $this->form->getAllRutasRiesgos($this->search);
        } else {
            $riesgos = [];
        }
        return view('livewire.catalogos.rutas-riesgos', compact('riesgos'));
    }


    #[On('save-riesgo')]
    public function save()
    {
        $res = $this->form->store(3);

        if ($res == 1) {
            $this->dispatch('success-riesgo', "El nombre de la riesgo se agrego al catalogo.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    public $riesgo_id = 0;
    public $riesgo;

    public function setRiesgo(CtgRutasRiesgo $riesgo)
    {
        $this->limpiar();
        $this->form->name = $riesgo->name;
        $this->riesgo_id = $riesgo->id;
        $this->riesgo = $riesgo;

        $this->dispatch('edit-riesgos');
    }

    public function limpiar()
    {
        $this->resetValidation();
        $this->form->name = '';
        $this->riesgo_id = 0;
        $this->riesgo = '';
    }

    #[On('update-riesgo')]
    public function update()
    {

        $res =  $this->form->update($this->riesgo);
        if ($res == 1) {
            $this->dispatch('success-riesgo', "El nombre del riesgo se actualizo con exito.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('delete-riesgo')]
    public function delete(CtgRutasRiesgo $riesgo)
    {
        $res =  $this->form->delete($riesgo);
        if ($res == 1) {
            $this->dispatch('success-riesgo', "El nombre del riesgo se dio de baja correctamente.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-riesgo')]
    public function baja(CtgRutasRiesgo $riesgo)
    {
        $this->form->baja($riesgo, 3);
        $this->dispatch('success-riesgo', "El nombre del riesgo se dio de baja correctamente.");
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgRutasRiesgo $riesgo)
    {
        $this->form->reactivar($riesgo, 3);
        $this->dispatch('success-riesgo', "El nombre del riesgo se restauro correctamente.");
    }
}
