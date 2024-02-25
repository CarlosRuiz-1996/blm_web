<?php

namespace App\Livewire;

use Livewire\Component;

class CrearUsuario extends Component
{
    public $count = 0;
    public function render()
    {
        return view('livewire.crear-usuario');
    }
 
    public function increment()
    {
        $this->count++;
    }
    public function decrement()
    {
        $this->count--;
    }
}
