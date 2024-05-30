<?php

namespace App\Livewire\Boveda;

use App\Models\Ruta;
use Livewire\Component;

class RutaRecolecta extends Component
{
    public function render()
    {
        $serviciosTerinados = Ruta::where('ctg_rutas_estado_id',4)->paginate(10);
        return view('livewire.boveda.ruta-recolecta', compact('serviciosTerinados'));
    }
}
