<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\cumplimiento;
use App\Models\expediente_digital;
use App\Models\validacioncumplimiento;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AltaValidaCumplimiento extends Component
{
    public $expedienteId;
    public $datosCliente;
    public $razonSocial;
    public $cumple=[];
    public $rfc;
    public $ctg_tipo_cliente_id;
    public $nombreContacto;
    public $puesto;
    public $tipocliente;
    public $nombreUsuario;
    public $documentosexpediente;
    public $idcumplimiento;
    public $nota = [];


    public function mount($expedienteId)
    {
        $this->cumple=[];
        $this->datosCliente = DB::table('users as us')
        ->join('clientes as cl', 'cl.user_id', '=', 'us.id')
        ->join('ctg_tipo_cliente as ctp', 'ctp.id', '=', 'cl.ctg_tipo_cliente_id')
        ->select('us.name', 'us.paterno', 'us.materno', 'cl.puesto', 'cl.razon_social', 'cl.rfc_cliente', 'cl.id', 'ctp.name as tipo_cliente')
        ->where('cl.id', $expedienteId) 
        ->first(); 
        


    if ($this->datosCliente) {
        $this->razonSocial = $this->datosCliente->razon_social;
        $this->rfc = $this->datosCliente->rfc_cliente;
        $this->ctg_tipo_cliente_id = $this->datosCliente->tipo_cliente;
        $this->nombreContacto = $this->datosCliente->name.' '.$this->datosCliente->paterno.' '.$this->datosCliente->materno;
        $this->puesto = $this->datosCliente->puesto;
        $this->cargarDocumentosExpediente($expedienteId);
    }
       
    }
    public function render()
    {
        return view('livewire.alta-valida-cumplimiento');
    }
    public function cargarDocumentosExpediente($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();
    
        if ($datosExpediente) {
            $this->documentosexpediente = DB::table('expediente_documentos as exp')
                ->join('ctg_documentos as ctgdoc', 'exp.ctg_documentos_id', '=', 'ctgdoc.id')
                ->select('exp.id', 'exp.expediente_digital_id', 'exp.document_name', 'ctgdoc.name')
                ->where('exp.expediente_digital_id', $datosExpediente->id)
                ->get();
            $this->idcumplimiento=cumplimiento::where('expediente_digital_id',$datosExpediente->id)->first();
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }

    public function updatedCumple($value, $documentoId)
    {
        // Busca un registro existente para el documento y el cumplimiento
        $registroExistente = validacioncumplimiento::where('cumplimiento_id', $this->idcumplimiento->id)
            ->where('expediente_documentos_id', $documentoId)
            ->first();
    
            if ($registroExistente) {
                // Si el registro existe, actualízalo
                $registroExistente->update([
                    'cumple' => $value,
                ]);
            } else {
                // Si el registro no existe, créalo
                validacioncumplimiento::create([
                    'cumplimiento_id' => $this->idcumplimiento->id,
                    'expediente_documentos_id' => $documentoId,
                    'cumple' => $value,
                    'status_validacion_doc_cumplimiento'=>1,
                ]);
            }
    }
    public function updatedNota($documentoId)
{
     // Verifica si $documentoId existe como clave en el array $this->nota
     if (array_key_exists($documentoId, $this->nota)) {
        // Obtén el valor actualizado de la nota
        $notaActualizada = $this->nota[$documentoId];
    } else {
        // Maneja el caso en el que $documentoId no existe en el array
        // Puedes agregar alguna lógica adicional según tus necesidades
        $notaActualizada = '';
    }

    // Busca y actualiza el registro existente para el documento y el cumplimiento
    $registroExistente = validacioncumplimiento::where('cumplimiento_id', $this->idcumplimiento->id)
        ->where('expediente_documentos_id', $documentoId)
        ->first();

    if ($registroExistente) {
        // Si el registro existe, actualízalo
        $registroExistente->update([
            'nota' => $notaActualizada,
        ]);
    } else {
        // Si el registro no existe, créalo
        validacioncumplimiento::create([
            'cumplimiento_id' => $this->idcumplimiento->id,
            'expediente_documentos_id' => $documentoId,
            'cumple' => 0, // Ajusta según tus necesidades
            'nota' => $notaActualizada,
            'status_validacion_doc_cumplimiento' => 1,
        ]);
    }
}

    
}
