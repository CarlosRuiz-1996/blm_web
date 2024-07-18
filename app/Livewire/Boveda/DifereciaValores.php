<?php

namespace App\Livewire\Boveda;

use App\Models\Inconsistencias;
use Livewire\Component;

class DifereciaValores extends Component
{
    public $readyToLoad = false;

    public function render()
    {
        if ($this->readyToLoad) {

            $diferencias = Inconsistencias::paginate(10);
        } else {
            $diferencias = [];
        }
        return view('livewire.boveda.diferecia-valores', compact('diferencias'));
    }
    public function loadDiferencias()
    {
        $this->readyToLoad = true;
    }
}
