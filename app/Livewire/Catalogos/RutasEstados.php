<?php

namespace App\Livewire\Catalogos;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\Catalogos\RutasForm;
use App\Models\CtgRutasEstado;

class RutasEstados extends Component
{

    public RutasForm $form;

    public function render()
    {
        $estados= $this->form->getAllRutasEstados();
        return view('livewire.catalogos.rutas-estados',compact('estados'));
    }

    #[On('save-estado')]
    public function save()
    {
        $this->form->store(1);
        $this->dispatch('success-estado', "El nombre del estado se agrego al catalogo.");
    }

    public $estado_id = 0;
    public $estado;

    public function setEstado(CtgRutasEstado $estado)
    {
        $this->limpiar();
        $this->form->name = $estado->name;
        $this->estado_id = $estado->id;
        $this->estado = $estado;

        $this->dispatch('edit-estados');
    }

    public function limpiar()
    {
        $this->form->name = '';
        $this->estado_id = 0;
        $this->estado = '';
        $this->dispatch('datatable');
    }

    #[On('update-estado')]
    public function update()
    {
        $this->form->update($this->estado);
        $this->dispatch('success-estado', "El nombre de la ruta se actualizo con exito.");
    }

    #[On('delete-estado')]
    public function delete(CtgRutasEstado $estado)
    {
        $res =  $this->form->delete($estado);
        if ($res == 1) {
            $this->dispatch('success-estado', "El estado de la ruta se dio de baja correctamente.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-estado')]
    public function baja(CtgRutasEstado $estado)
    {
        $this->form->baja($estado,1);
        $this->dispatch('success-estado', "El estado de la ruta se dio de baja correctamente.");
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgRutasEstado $estado)
    {
        $this->form->reactivar($estado,1);
        $this->dispatch('success-estado', "El estado de la ruta se restauro correctamente.");
    }
}
