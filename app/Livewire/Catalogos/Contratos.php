<?php

namespace App\Livewire\Catalogos;

use App\Models\Ctg_Contratos;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Contratos extends Component
{
    use WithFileUploads;
public $documentomodal;
public $nombredocumento;
public $docword;
public $cargado=false;
use WithFileUploads;

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

    #[On('save-dia')]
    public function guardarContrato()
    {

        $this->resetValidation();
        $this->documentomodal = '';
        $this->nombredocumento = '';
    }

    public function cargarImagen()
    {
        // Validar la imagen cargada si es necesario
        $this->validate([
            'docword' => 'required', // Por ejemplo, valida que sea una imagen y su tamaño máximo sea 1MB
        ]);
        $this->cargado=true;
        
    }



}
