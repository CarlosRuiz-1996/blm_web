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
    public $cargado = false;
    public $imageKey;
    public $imageKey2;
    public $docwordeditar;
    public $nombredocumentoeditar;
    public $fileName;
    public $idEditar;
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
        $this->imageKey2 = rand();
    }
    public function limpiarmodal2()
    {
        $this->resetValidation();
        $this->nombredocumentoeditar = '';
        $this->imageKey2 = rand();
        $this->fileName="";
    }

    #[On('save-contrato')]
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
                'path' => $nombreLimpio . '.docx',
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
    public function eliminarContrato($id)
    {
        try {
            DB::beginTransaction();
        $contratoctg = Ctg_Contratos::find($id);
        $contratoctg->status_contrato = 0;
        $contratoctg->save();
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se deshabilito este tipo de contrato'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Error al deshabilitar tipo de contrato'], ['tipomensaje' => 'error']);
        }
    }
    public function reactivarContrato($id)
    {
        try {
            DB::beginTransaction();
        $contratoctg = Ctg_Contratos::find($id);
        $contratoctg->status_contrato = 1;
        $contratoctg->save();
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se habilito este tipo de contrato'], ['tipomensaje' => 'success']);
    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Error al habilitar tipo de contrato'], ['tipomensaje' => 'error']);
    }
    }
    #[On('update-contrato')]
    public function editarContrato()
    {
        $this->validate([
            'idEditar'=>'required',
            'nombredocumentoeditar' => 'required|string|unique:ctg_contratos,nombre',
            'docwordeditar' => 'required|file|mimes:docx',
        ]);
        try {
            DB::beginTransaction();
            $textoRecibido = $this->nombredocumentoeditar;
            $nombreLimpio = str_replace(' ', '_', $textoRecibido);
            $this->docwordeditar->storeAs(path: 'contratos/', name: $nombreLimpio . '.docx');

            Ctg_Contratos::create([
                'nombre' => $this->nombredocumentoeditar,
                'path' => $nombreLimpio . '.docx',
                'status_contrato' => 1,
            ]);

            DB::commit();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se ha agregado el archivo: ' . $nombreLimpio . '.docx'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Fallo al subir archivo'], ['tipomensaje' => 'error']);
        }
        $this->documentomodal = '';
        $this->nombredocumentoeditar = '';
        $this->imageKey2 = rand();
    }

    public function abrirModalEditar($id){
        $this->limpiarmodal2();
        $contratoAditar = Ctg_Contratos::find($id);
        $this->nombredocumentoeditar=$contratoAditar->nombre;
        $this->idEditar=$contratoAditar->id;
    }
    public function updatedDocwordeditar()
    {
        // Aquí puedes acceder al nombre del archivo
        $fileName = $this->docwordeditar->getClientOriginalName();
        // Puedes almacenar el nombre en una propiedad para mostrarlo en la vista
        $this->fileName = $fileName;
    }
}
