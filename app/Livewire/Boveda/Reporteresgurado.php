<?php

namespace App\Livewire\Boveda;

use App\Models\ResguardoResporte;
use Livewire\Component;
use Livewire\WithPagination;

class Reporteresgurado extends Component
{
    use WithPagination;
    
    public function render()
    {
        $repotes=ResguardoResporte::paginate(10);
        return view('livewire.boveda.reporteresgurado',compact('repotes'));
    }
}
