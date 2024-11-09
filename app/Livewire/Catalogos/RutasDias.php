<?php

namespace App\Livewire\Catalogos;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\Catalogos\RutasForm;
use App\Models\CtgRutaDias;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class RutasDias extends Component
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
            $dias = $this->form->getAllRutasDias($this->search);
        } else {
            $dias = [];
        }
        return view('livewire.catalogos.rutas-dias', compact('dias'));
    }




    public $dias_array = array('LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO');
    #[On('save-dia')]
    public function save()
    {

        $sin_acentos = strtoupper($this->removeAccents($this->form->name));
        if (in_array($sin_acentos, $this->dias_array)) {
            $res = $this->form->store(4);
            if ($res == 1) {
                $this->dispatch('success-dia', "El nombre del dia se agrego al catalogo.");
            } else {
                $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
            }
        } else {
            $this->dispatch('error', "Este no es un dia de la semana.");
        }
    }
    private function removeAccents($str)
    {
        //quita acentos del texto que ingrso
        return strtr(
            utf8_decode($str),
            utf8_decode('ÁÉÍÓÚÑáéíóúñ'),
            'AEIOUNaeioun'
        );
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
        $this->resetValidation();
        $this->form->name = '';
        $this->dia_id = 0;
        $this->dia = '';
    }

    #[On('update-dia')]
    public function update()
    {



        $sin_acentos = strtoupper($this->removeAccents($this->form->name));
        if (in_array($sin_acentos, $this->dias_array)) {
            $res = $this->form->update($this->dia);
            if ($res == 1) {
                $this->dispatch('success-dia', "El nombre del dia se actualizo con exito.");
            } else {
                $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
            }
        } else {
            $this->dispatch('error', "Este no es un dia de la semana.");
        }
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
        $this->form->baja($dia, 4);
        $this->dispatch('success-dia', "El nombre del dia se dio de baja correctamente.");
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgRutaDias $dia)
    {
        $this->form->reactivar($dia, 4);
        $this->dispatch('success-dia', "El nombre del dia se restauro correctamente.");
    }
}
