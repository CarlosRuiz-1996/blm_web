<?php

namespace App\Livewire\Catalogos;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\Catalogos\RutasForm;
use App\Models\CtgRutaDias;

class RutasDias extends Component
{

    public RutasForm $form;

    public function render()
    {
        $dias= $this->form->getAllRutasDias();

        return view('livewire.catalogos.rutas-dias', compact('dias'));
    }

    
    
    #[On('save-dia')]
    public function save()
    {
        $this->form->store(4);
        $this->dispatch('success-dia', "El nombre del dia se agrego al catalogo.");
    }

    public $dia_id = 0;
    public $dia;

    public function setDia(CtgRutaDias $dia)
    {
        $this->limpiar();
        $this->form->name = $dia->name;
        $this->dia_id = $dia->id;
        $this->dia = $dia;

        $this->dispatch('edit-dias');
    }

    public function limpiar()
    {
        $this->form->name = '';
        $this->dia_id = 0;
        $this->dia = '';
        $this->dispatch('datatable');
    }

    #[On('update-dia')]
    public function update()
    {
        $this->form->update($this->dia);
        $this->dispatch('success-dia', "El nombre del dia se actualizo con exito.");
    }

    #[On('delete-dia')]
    public function delete(CtgRutaDias $dia)
    {
        $res =  $this->form->delete($dia);
        if ($res == 1) {
            $this->dispatch('success-dia', "El nombre del dia se dio de baja correctamente.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-dia')]
    public function baja(CtgRutaDias $dia)
    {
        $this->form->baja($dia,4);
        $this->dispatch('success-dia', "El nombre del dia se dio de baja correctamente.");
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgRutaDias $dia)
    {
        $this->form->reactivar($dia,4);
        $this->dispatch('success-dia', "El nombre del dia se restauro correctamente.");
    }
}
