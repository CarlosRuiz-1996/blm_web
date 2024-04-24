<?php

namespace App\Livewire\Operaciones\Listados;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;

class AllRutas extends Component
{
    public RutaForm $form;

    public function render()
    {
        $rutas = $this->form->getAllRutas();

        return view('livewire.operaciones.listados.all-rutas', compact('rutas'));
    }
}
