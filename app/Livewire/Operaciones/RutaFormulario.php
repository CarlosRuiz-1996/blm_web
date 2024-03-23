<?php

namespace App\Livewire\Operaciones;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use Livewire\Attributes\On;

class RutaFormulario extends Component
{

    public $op;
    public $ruta;
    public RutaForm $form;

    #[On('render-rutas')]
    public function render()
    {
        $rutas = $this->form->getCtgRutas();
        return view('livewire.operaciones.ruta-formulario',compact('rutas'));
    }
}
