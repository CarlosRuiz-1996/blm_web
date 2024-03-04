<?php

namespace App\Livewire;

use Livewire\Component;

class ClienteCabecera extends Component
{
    public $cliente;
    public function render()
    {
        return view('livewire.cliente-cabecera');
    }
}
