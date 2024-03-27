<?php

namespace App\Livewire\Operaciones;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
class RutaList extends Component
{
    public RutaForm $form;
    public function render()
    {
        $rutas = $this->form->getAllRutas();
        return view('livewire.operaciones.ruta-list',compact('rutas'));
    }
}
