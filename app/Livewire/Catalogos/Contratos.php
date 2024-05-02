<?php

namespace App\Livewire\Catalogos;

use App\Models\Ctg_Contratos;
use Illuminate\Support\Facades\DB;
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
public $imageKey;
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
        $this->validate([
            'nombredocumento' => 'required|string|unique:ctg_contratos,nombre',
            'docword' => 'required|file|mimes:docx',
        ]);
        try {
            DB::beginTransaction();
            $textoRecibido = $this->nombredocumento;
            $nombreLimpio = str_replace(' ', '_', $textoRecibido);
            $this->docword->storeAs(path: 'contratos/', name: $nombreLimpio . '.docx');
            
                Ctg_Contratos::create([
                    'nombre' => $this->nombredocumento,
                    'path' => $nombreLimpio .'.docx',
                    'status_contrato' => 1,
                ]);
            
            DB::commit();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se ha agregado el archivo: ' . $nombreLimpio . '.docx'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Fallo al subir archivo'], ['tipomensaje' => 'error']);
        }
        $this->documentomodal = '';
        $this->nombredocumento = '';
        $this->imageKey = rand();
    }

}
