<?php

namespace App\Livewire\Operaciones;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use Livewire\Attributes\On;

class RutaGestion extends Component
{

    public $op;
    public $rutas;
    public RutaForm $form;
    public $dia_select = false;

    public function mount($ruta = null)
    {
        if ($ruta) {
            $ruta = Ruta::find($ruta);
            $this->form->ruta = $ruta;
            $this->form->hora_inicio = $ruta->hora_inicio;
            $this->form->hora_fin = $ruta->hora_fin;
            $this->form->ctg_rutas_id = $ruta->nombre->name;
            $this->form->ctg_ruta_dia_id = $ruta->dia->name;
        }
    }

    // public $dia;
    #[On('render-rutas')]
    public function render()
    {
        $dias = $this->form->getCtgDias();
        return view('livewire.operaciones.ruta-gestion', compact('dias'));
    }

    public function updated($property)
    {
        if ($property === 'form.ctg_ruta_dia_id') {
            $this->reset('form.hora_inicio', 'form.hora_fin', 'form.ctg_rutas_id');
            if ($this->form->ctg_ruta_dia_id != 0) {
                $this->dia_select = true;
                $this->rutas = $this->form->getCtgRutas($this->form->ctg_ruta_dia_id);
            } else {
                $this->dia_select = false;
            }
        }
    }
    #[On('save-ruta')]
    public function save()
    {
        $this->validate([
            'form.ctg_rutas_id' => 'required',
            'form.hora_inicio' => 'required',
            'form.ctg_ruta_dia_id' => 'required'
        ], [
            'form.ctg_rutas_id' => 'El nombre de la ruta es obligatorio',
            'form.hora_inicio' => 'La hora de inicio es obligatorio',
            'form.ctg_ruta_dia_id' => 'El dia de la ruta es obligatorio'
        ]);
        // $this->form->ruta = 1;
        $res =  $this->form->store();
        if ($res == 1) {
            $this->dispatch('success', ['La ruta se creo con exito', 'Ahora vamos complementarla',$this->form->ruta->id]);
        } else {
            $this->dispatch('error', 'Ocurrio un error, Intenta mas tarde');
        }
    }

    #[On('update-ruta')]
    public function update($accion)
    {

        if ($accion == 1) {

            $res = $this->form->boveda();

            if ($res == 1) {
                $this->dispatch('success',  ['La ruta paso al proceso de gestión en boveda']);
            } else {
                $this->dispatch('error', 'Hubo un error, intenta mas tarde');
            }
        }
    }
}
