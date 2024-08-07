<?php

namespace App\Livewire\Boveda;

use App\Models\Ruta;
use Livewire\Component;

class RutaRecolecta extends Component
{
    public $readyToLoad = false;

    public function render()
    {
        if ($this->readyToLoad) {

            $serviciosTerinados = Ruta::where('ctg_rutas_estado_id', 4)->paginate(10);
        } else {
            $serviciosTerinados = [];
        }
        return view('livewire.boveda.ruta-recolecta', compact('serviciosTerinados'));
    }

    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
}
