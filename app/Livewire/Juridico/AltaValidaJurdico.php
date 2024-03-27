<?php

namespace App\Livewire\Juridico;

use App\Models\Cotizacion;
use App\Models\expediente_digital;
use App\Models\juridico;
use App\Models\validacionjuridico;
use App\Models\validacionjuridicobene;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class AltaValidaJurdico extends Component
{
    use WithFileUploads;
    public $expedienteId;
    public $datosCliente;
    public $razonSocial;
    public $cumple = [];
    public $rfc;
    public $ctg_tipo_cliente_id;
    public $nombreContacto;
    public $puesto;
    public $tipocliente;
    public $nombreUsuario;
    public $documentosexpediente;
    public $idcumplimiento;
    public $nota = [];
    public $valornota;
    public $evidencias;
    public $pdfUrl;
    public $isOpen;
    public $documentoevidencia;
    public $notaevidencias = [];
    public $cumpleevidencias = [];
    public $documentosEvidencias;
    public $ctg_aceptado;
    public $ctg_rechazo;
    public $aceptado = [];
    public $idexpedientedig;
    public $aceptadoValid = [];
    public $aceptadoOrNegado;
    public $documentosexpedienteBene;
    public $notabene = [];
    public $cumplebene = [];
    public $valornotabene;


    public function mount($expedienteId)
    {
        $this->cumplebene = [];
        $this->notabene = [];
        $this->cumple = [];
        $this->nota = [];
        $this->notaevidencias = [];
        $this->cumpleevidencias = [];
        $this->datosCliente = DB::table('users as us')
            ->join('clientes as cl', 'cl.user_id', '=', 'us.id')
            ->join('ctg_tipo_cliente as ctp', 'ctp.id', '=', 'cl.ctg_tipo_cliente_id')
            ->select('us.name', 'us.paterno', 'us.materno', 'cl.puesto', 'cl.razon_social', 'cl.rfc_cliente', 'cl.id', 'ctp.name as tipo_cliente')
            ->where('cl.id', $expedienteId)
            ->first();
        $this->idexpedientedig = $expedienteId;



        if ($this->datosCliente) {
            $this->razonSocial = $this->datosCliente->razon_social;
            $this->rfc = $this->datosCliente->rfc_cliente;
            $this->ctg_tipo_cliente_id = $this->datosCliente->tipo_cliente;
            $this->nombreContacto = $this->datosCliente->name . ' ' . $this->datosCliente->paterno . ' ' . $this->datosCliente->materno;
            $this->puesto = $this->datosCliente->puesto;
            $this->cargarDocumentosExpediente($expedienteId);
            $this->cargarDeValidacion($expedienteId);
            $this->ActualizarCumplimiento($expedienteId);
            $this->obtenerCantidadValidados($expedienteId);
            $this->cargarDocumentosExpedienteBene($expedienteId);
            $this->cargarDeValidacionBene($expedienteId);
        }
    }
    public function render()
    {
        return view('livewire.juridico.alta-valida-jurdico');
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
            $this->idcumplimiento = juridico::where('expediente_digital_id', $datosExpediente->id)->first();
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }


    public function cargarDeValidacion($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpediente) {
            $this->valornota = DB::table('juridico as c')
                ->join('juridico_doc_validacion as val', 'c.id', '=', 'val.juridico_id')
                ->where('c.expediente_digital_id', $datosExpediente->id)
                ->select('val.expediente_documentos_id', 'val.nota', 'val.cumple')
                ->get();

            // Actualiza las propiedades $nota y $cumple
            foreach ($this->valornota as $notav) {
                $this->nota[$notav->expediente_documentos_id] = $notav->nota;
                $this->cumple[$notav->expediente_documentos_id] = $notav->cumple;
            }
        }
    }

    public function updatedCumple($value, $documentoId)
    {
        // Busca un registro existente para el documento y el cumplimiento
        $registroExistente = validacionjuridico::where('juridico_id', $this->idcumplimiento->id)
            ->where('expediente_documentos_id', $documentoId)
            ->first();

        if ($registroExistente) {
            // Si el registro existe, actualízalo
            $registroExistente->update([
                'cumple' => $value,
            ]);
        } else {
            // Si el registro no existe, créalo
            validacionjuridico::create([
                'juridico_id' => $this->idcumplimiento->id,
                'expediente_documentos_id' => $documentoId,
                'cumple' => $value,
                'status_validacion_doc_juridico' => 1,
            ]);
        }
        $this->obtenerCantidadValidados($this->idexpedientedig);
    }
    public function updatedNota($value, $documentoId)
    {
        // Verifica si $documentoId existe como clave en el array $this->nota
        if (array_key_exists($documentoId, $this->nota)) {
            // Obtén el valor actualizado de la nota
            $notaActualizada = $value;
        } else {
            // Maneja el caso en el que $documentoId no existe en el array
            // Puedes agregar alguna lógica adicional según tus necesidades
            $notaActualizada = '';
        }

        // Busca y actualiza el registro existente para el documento y el cumplimiento
        $registroExistente = validacionjuridico::where('juridico_id', $this->idcumplimiento->id)
            ->where('expediente_documentos_id', $documentoId)
            ->first();

        if ($registroExistente) {
            // Si el registro existe, actualízalo
            $registroExistente->update([
                'nota' => $notaActualizada,
            ]);
        } else {
            // Si el registro no existe, créalo
            validacionjuridico::create([
                'juridico_id' => $this->idcumplimiento->id,
                'expediente_documentos_id' => $documentoId,
                'cumple' => 0, // Ajusta según tus necesidades
                'nota' => $notaActualizada,
                'status_validacion_doc_juridico' => 1,
            ]);
        }
        $this->obtenerCantidadValidados($this->idexpedientedig);
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

    public function ActualizarCumplimiento($id)
    {
        $datosExpedientes = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpedientes) {
            $cumplimientos = juridico::where('expediente_digital_id', $datosExpedientes->id)->first();

            if ($cumplimientos) {
                // Si el registro existe, actualízalo
                $cumplimientos->update([
                    'status_juridico' => 2,
                ]);
            }
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }

    public function negadaValida()
    {
        $datosExpediente = expediente_digital::where('cliente_id', $this->idexpedientedig)->first();

        if ($datosExpediente) {
            $this->idcumplimiento = juridico::where('expediente_digital_id', $datosExpediente->id)->first();

            $this->idcumplimiento->update([
                'status_juridico' => 3,
                'dictamen' => 0,
                'fecha_dictamen' => now(),
            ]);
        }
        return redirect()->route('juridico.index');
    }

    public function aceptadaValida()
    {
        $datosExpediente = expediente_digital::where('cliente_id', $this->idexpedientedig)->first();

        if ($datosExpediente) {
            $this->idcumplimiento = juridico::where('expediente_digital_id', $datosExpediente->id)->first();
            $this->idcumplimiento->update([
                'status_juridico' => 3,
                'dictamen' => 1,
                'fecha_dictamen' => now(),
            ]);
        }

        $datosExpediente->juridico = 2;
        $datosExpediente->save();

        if ($datosExpediente->cumplimiento == 2) {
            $datosExpediente->status_expediente_digital = 2;
            $datosExpediente->save();
        }
        if($datosExpediente->juridico == 2 && $datosExpediente->cumplimiento = 2){
            Cotizacion::where('cliente_id', $datosExpediente->cliente_id)->update(['status_cotizacion' => 3]);
        }
        return redirect()->route('juridico.index');
    }
    public function obtenerCantidadValidados($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();
        $consulta = DB::table('expediente_documentos as exp')
            ->leftJoin('juridico_doc_validacion as val', 'val.expediente_documentos_id', '=', 'exp.id')
            ->select('val.cumple')
            ->where('exp.expediente_digital_id', $datosExpediente->id)
            ->get();
        $consulta2 = DB::table('expediente_documentos_benf as exp')
            ->leftJoin('juridico_doc_validacion_beneficiario as val', 'val.expediente_documentos_benf_id', '=', 'exp.id')
            ->select('val.cumple')
            ->where('exp.expediente_digital_id', $datosExpediente->id)
            ->get();

        // Inicializa contadores
        $totalRegistros = count($consulta);
        $registrosNull = 0;
        $registros1 = 0;
        $registros0 = 0;
        //contadores bene
        $totalRegistrosbene = count($consulta2);
        $registrosNullbene = 0;
        $registros1bene = 0;
        $registros0bene = 0;

        // Recorre los resultados para contar
        foreach ($consulta as $registro) {
            if ($registro->cumple === null) {
                $registrosNull++;
            } elseif ($registro->cumple == 1) {
                $registros1++;
            } elseif ($registro->cumple == 0) {
                $registros0++;
            }
        }
        //recorre beneficiarios documentos
        if ($totalRegistrosbene > 0) {
            foreach ($consulta2 as $registro2) {
                if ($registro2->cumple === null) {
                    $registrosNullbene++;
                } elseif ($registro2->cumple == 1) {
                    $registros1bene++;
                } elseif ($registro2->cumple == 0) {
                    $registros0bene++;
                }
            }
        }

        if ($registrosNull == $totalRegistros) {
            $this->aceptadoOrNegado = 0;  // Todos los registros son 0
        } elseif ($registrosNull >= 1 || $registros0 >= 1) {
            $this->aceptadoOrNegado = 1;  // Hay registros que son null o 0
        } else {
            $this->aceptadoOrNegado = 2;  // Todos los registros son 1
        }
        //valida beneficiarios documentos
        if ($totalRegistrosbene > 0) {
            if ($this->aceptadoOrNegado == 2) {
                if ($registrosNullbene == $totalRegistrosbene) {
                    $this->aceptadoOrNegado = 0;  // Todos los registros son 0
                } elseif ($registrosNullbene >= 1 || $registros0bene >= 1) {
                    $this->aceptadoOrNegado = 1;  // Hay registros que son null o 0
                } else {
                    $this->aceptadoOrNegado = 2;  // Todos los registros son 1
                }
            } else {
                $this->aceptadoOrNegado = 1;
            }
        }
    }

    //documentos beneficiario
    public function cargarDocumentosExpedienteBene($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpediente) {
            $this->documentosexpedienteBene = DB::table('expediente_documentos_benf as exp')
                ->join('ctg_documentos_benf as ctgdoc', 'exp.ctg_documentos_benf_id', '=', 'ctgdoc.id')
                ->select('exp.id', 'exp.expediente_digital_id', 'exp.document_name', 'ctgdoc.name')
                ->where('exp.expediente_digital_id', $datosExpediente->id)
                ->get();
            $this->idcumplimiento = juridico::where('expediente_digital_id', $datosExpediente->id)->first();
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }
    public function cargarDeValidacionBene($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpediente) {
            $this->valornotabene = DB::table('juridico as c')
                ->join('juridico_doc_validacion_beneficiario as val', 'c.id', '=', 'val.juridico_id')
                ->where('c.expediente_digital_id', $datosExpediente->id)
                ->select('val.expediente_documentos_benf_id', 'val.nota', 'val.cumple')
                ->get();

            // Actualiza las propiedades $nota y $cumple
            foreach ($this->valornotabene as $notavbene) {
                $this->notabene[$notavbene->expediente_documentos_benf_id] = $notavbene->nota;
                $this->cumplebene[$notavbene->expediente_documentos_benf_id] = $notavbene->cumple;
            }
        }
    }

    public function updatedCumpleBene($value, $documentoId)
    {

        // Busca un registro existente para el documento y el cumplimiento
        $registroExistente = validacionjuridicobene::where('juridico_id', $this->idcumplimiento->id)
            ->where('expediente_documentos_benf_id', $documentoId)
            ->first();

        if ($registroExistente) {
            // Si el registro existe, actualízalo
            $registroExistente->update([
                'cumple' => $value,
            ]);
        } else {
            // Si el registro no existe, créalo
            validacionjuridicobene::create([
                'juridico_id' => $this->idcumplimiento->id,
                'expediente_documentos_benf_id' => $documentoId,
                'cumple' => $value,
                'status_validacion_doc_juridico_beneficiario' => 1,
            ]);
        }
        $this->obtenerCantidadValidados($this->idexpedientedig);
    }
    public function updatedNotaBene($value, $documentoId)
    {
        // Verifica si $documentoId existe como clave en el array $this->nota
        if (array_key_exists($documentoId, $this->notabene)) {
            // Obtén el valor actualizado de la nota
            $notaActualizada = $value;
        } else {
            // Maneja el caso en el que $documentoId no existe en el array
            // Puedes agregar alguna lógica adicional según tus necesidades
            $notaActualizada = '';
        }

        // Busca y actualiza el registro existente para el documento y el cumplimiento
        $registroExistente = validacionjuridicobene::where('juridico_id', $this->idcumplimiento->id)
            ->where('expediente_documentos_benf_id', $documentoId)
            ->first();

        if ($registroExistente) {
            // Si el registro existe, actualízalo
            $registroExistente->update([
                'nota' => $notaActualizada,
            ]);
        } else {
            // Si el registro no existe, créalo
            validacionjuridico::create([
                'juridico_id' => $this->idcumplimiento->id,
                'expediente_documentos_benf_id' => $documentoId,
                'cumple' => 0, // Ajusta según tus necesidades
                'nota' => $notaActualizada,
                'status_validacion_doc_juridico_beneficiario' => 1,
            ]);
        }
        $this->obtenerCantidadValidados($this->idexpedientedig);
    }
}
