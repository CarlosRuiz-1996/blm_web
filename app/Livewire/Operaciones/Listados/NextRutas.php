<?php

namespace App\Livewire\Operaciones\Listados;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;

class NextRutas extends Component
{
    public RutaForm $form;

    public function render()
    {   
        $rutas =$this->form->getNextRutas();
        return view('livewire.operaciones.listados.next-rutas');
    }
}
