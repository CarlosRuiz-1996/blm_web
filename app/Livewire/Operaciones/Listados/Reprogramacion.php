<?php

namespace App\Livewire\Operaciones\Listados;

use App\Models\RutaServicio;
use Livewire\Component;
use Livewire\WithPagination;
class Reprogramacion extends Component
{
    use WithPagination;
    public function render()
    {
        $reprogramacion = RutaServicio::where('status_ruta_servicios',0)->paginate(10);
        return view('livewire.operaciones.listados.reprogramacion',compact('reprogramacion'));
    }
}
