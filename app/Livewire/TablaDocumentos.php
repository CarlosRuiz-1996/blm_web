<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\ctg_documentos;
use App\Models\ctg_documentos_beneficiarios;
use App\Models\expediente_digital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TablaDocumentos extends Component
{
    public $tuVariable;
    public $id;
    public $documentos;
    public $documentos_beneficiarios;
    public $documentoid;
    public $documentoSelec;
    public $documentoidbene;
    public $documentoSelecbene;
    public $documentosexpediente;
    public $documentosexpedienteBene;
    public $rfc;
    public $pdfUrl;
    public $isOpen = false;
    public $cliente;

    public $cliente_status;  
    public $expediente;
    public function mount(Cliente $cliente,$cliente_status)
    {
        $this->expediente = expediente_digital::where('cliente_id',$cliente->id)->first();
        $this->cliente = $cliente;
        $this->id = $cliente->id;
        $this->rfc = $cliente->rfc_cliente;
        $this->documentos_beneficiarios=ctg_documentos_beneficiarios::all();
        $this->documentos = ctg_documentos::where('ctg_tipo_cliente_id', $cliente->ctg_tipo_cliente_id)->get();
        $this->cargarDocumentosExpediente();
        $this->cargarDocumentosExpedienteBene();
    }
    public function render()
    {
        return view('livewire.tabla-documentos');
    }

    public function cargarDocumentosExpediente()
    {
        // Obtener los documentos del expediente digital actualizado
        $this->documentosexpediente = DB::table('expediente_digital as exp')
            ->join('expediente_documentos as ex', 'ex.expediente_digital_id', '=', 'exp.id')
            ->where('exp.cliente_id', $this->id)
            ->select('ex.ctg_documentos_id', 'ex.document_name')
            ->get();
    }
    public function cargarDocumentosExpedienteBene()
    {
        // Obtener los documentos del expediente digital actualizado
        $this->documentosexpedienteBene = DB::table('expediente_digital as exp')
            ->join('expediente_documentos_benf as ex', 'ex.expediente_digital_id', '=', 'exp.id')
            ->where('exp.cliente_id', $this->id)
            ->select('ex.ctg_documentos_benf_id', 'ex.document_name')
            ->get();
    }
    
    public function openModal($pdfUrl)
    {
        $this->pdfUrl = $pdfUrl;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->pdfUrl = "";
        $this->isOpen = false;
    }
}
