<?php

namespace App\Livewire\Catalogos;

use App\Models\Ctg_Contratos;
use Livewire\Component;
use Livewire\WithFileUploads;

class Contratos extends Component
{
    use WithFileUploads;
public $documentomodal;
public $nombredocumento;
    public function render()
    {
        $contratos = Ctg_Contratos::all();
        return view('livewire.catalogos.Contratos', compact('contratos'));
    }
    public function limpiar()
    {
        $this->resetValidation();
        $this->documentomodal = '';
        $this->nombredocumento = '';
    }


    public function guardarContrato()
    {
        $this->resetValidation();
        $this->documentomodal = '';
        $this->nombredocumento = '';
    }



}
