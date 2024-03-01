<?php

namespace App\Livewire;

use App\Models\ctg_documentos;
use App\Models\ctg_documentos_beneficiarios;
use Livewire\Component;
use Livewire\WithFileUploads;

class AltaSolicitudCumplimiento extends Component
{
    public $tuVariable;
    public $documentos;
    public $documentos_beneficiarios;
    public $doc_ben_url;
    public $archivos = [];
    public $rfc="sadfsfsd";
    use WithFileUploads;

    
    public function mount(){
        $this->tuVariable="";
        $this->documentos=ctg_documentos::where('ctg_tipo_cliente_id',1)->get();
        $this->documentos_beneficiarios=ctg_documentos_beneficiarios::all();
    }
    public function render()
    {
        return view('livewire.alta-solicitud-cumplimiento');
    }


    public function agregarArchivo($documentoId)
    {
        $nombre=ctg_documentos_beneficiarios::where('id',$documentoId)->get();
        foreach ($this->archivos as $photo) {
            $photo->storeAs(path: 'documentos/'.$this->rfc, name: $nombre[0]->name.'.pdf');
        }
    }
}
