<?php

namespace App\Livewire\Boveda;

use App\Models\ResguardoResporte;
use Livewire\Component;

class Reporteresgurado extends Component
{
    public function render()
    {
        $repotes=ResguardoResporte::paginate(10);
        return view('livewire.boveda.reporteresgurado',compact('repotes'));
    }
}
