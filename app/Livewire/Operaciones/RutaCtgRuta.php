<?php

namespace App\Livewire\Operaciones;

use App\Models\CtgRutas;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class RutaCtgRuta extends Component
{

    #[Validate('required', message: 'Debe escribir el numbre de la ruta')]
    public $name;
    public function render()
    {
        return view('livewire.operaciones.ruta-ctg-ruta');
    }
    #[On('save-ctg-ruta')]
    public function save()
    {
        $this->validate();
        CtgRutas::create($this->only(['name']));
        $this->dispatch('render-rutas');

        $this->dispatch('success-ctg', "El nombre de la ruta se agrego al catalogo.");


    }
}
