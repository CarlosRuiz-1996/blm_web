<?php

namespace App\Livewire\Anexo1;

use App\Models\Cotizacion;
use Livewire\Component;

class ListadosAnexo1 extends Component
{
    public function render()
    {
        $solicitudes = Cotizacion::where('status_cotizacion','=',2)->get();
        $terminadas = Cotizacion::where('status_cotizacion','=',3)->get();

        return view('livewire.anexo1.listados-anexo1', compact('solicitudes','terminadas'));
    }
}
