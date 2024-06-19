<?php

namespace App\Livewire\Boveda;

use App\Models\Ruta;
use Livewire\Component;

class RutaProcesar extends Component
{
    public $ruta;

    public function mount(Ruta $ruta)
    {
        $this->ruta = $ruta;
    }
    public function render()
    {
        return view('livewire.boveda.ruta-procesar');
    }
}
