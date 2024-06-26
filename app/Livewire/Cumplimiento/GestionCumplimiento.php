<?php

namespace App\Livewire\Cumplimiento;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\ctg_aceptado;
use App\Models\ctg_rechazo;
use App\Models\cumplimiento;
use App\Models\cumplimiento_aceptado;
use App\Models\cumplimiento_rechazo;
use App\Models\cumplimientoEvidencias;
use App\Models\expediente_digital;
use App\Models\validacioncumplimiento;
use App\Models\validacioncumplimientobene;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class GestionCumplimiento extends Component
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
            $this->cargarDocumentosEvidencia($expedienteId);
            $this->ActualizarCumplimiento($expedienteId);
            $this->catalogoAceptado();
            $this->catalogoRechazado();
            $this->obtenerCantidadValidados($expedienteId);
            $this->cargarDocumentosExpedienteBene($expedienteId);
            $this->cargarDeValidacionBene($expedienteId);
        }
    }
    public function render()
    {
        return view('livewire.cumplimiento.gestion-cumplimiento');
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
            $this->idcumplimiento = cumplimiento::where('expediente_digital_id', $datosExpediente->id)->first();
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }
    public function cargarDocumentosEvidencia($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpediente) {
            $this->idcumplimiento = cumplimiento::where('expediente_digital_id', $datosExpediente->id)->first();
            $this->documentosEvidencias = cumplimientoEvidencias::where('cumplimiento_id', $this->idcumplimiento->id)->get();
            foreach ($this->documentosEvidencias as $notavevi) {
                $this->notaevidencias[$notavevi->id] = $notavevi->nota;
                $this->cumpleevidencias[$notavevi->id] = $notavevi->cumple;
            }
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }

    public function cargarDeValidacion($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpediente) {
            $this->valornota = DB::table('cumplimiento as c')
                ->join('cumplimiento_doc_validacion as val', 'c.id', '=', 'val.cumplimiento_id')
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
        $exp = expediente_digital::where('cliente_id', $this->idexpedientedig)->first();


        if ($exp->status_expediente_digital == 2) {
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
                    'status_validacion_doc_cumplimiento' => 1,
                ]);
            }
            $this->obtenerCantidadValidados($this->idexpedientedig);
        } else {
            $this->dispatch('error', 'Algun usuario esta subiendo documentos');
        }
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


    public function agregarEvidencia()
    {
        $this->validate([
            'documentoevidencia' => 'required|file|mimes:pdf|max:10240', // Ajusta según tus necesidades
        ]);
        try {
            DB::beginTransaction();

            $nombreLimpio = 'evidencias';
            $this->documentoevidencia->storeAs(path: 'documentos/' . $this->rfc . '/evidencias', name: $nombreLimpio . '.pdf');

            $datosevidencia = cumplimientoEvidencias::where('cumplimiento_id', $this->idcumplimiento->id)->first();

            if (!$datosevidencia) {
                cumplimientoEvidencias::create([
                    'cumplimiento_id' =>  $this->idcumplimiento->id,
                    'dictamen' => 0,
                    'document_name' => $nombreLimpio . '.pdf',
                    'status_cumplimiento_evidencias' => 1,
                    'cumple' => 0,
                    'nota' => '',
                ]);
            } else {
                $datosevidencia->update([
                    'document_name' => $nombreLimpio . '.pdf',
                    'status_expediente_doc' => 1,
                ]);
            }

            $exp = expediente_digital::where('cliente_id', $this->idexpedientedig)->first();

            $this->cargarDocumentosEvidencia($exp->cliente_id);
            DB::commit();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se ha agregado el archivo: ' . $nombreLimpio . '.pdf'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Fallo al subir archivo'], ['tipomensaje' => 'error']);
        }
    }



    public function updatedCumpleevidencias($value)
    {
        // Busca un registro existente para el documento y el cumplimiento
        $registroExistente = cumplimientoEvidencias::where('cumplimiento_id', $this->idcumplimiento->id)->first();

        if ($registroExistente) {
            // Si el registro existe, actualízalo
            $registroExistente->update([
                'cumple' => $value,
            ]);
        } else {
        }
    }
    public function updatedNotaevidencias($value, $documentoId)
    {
        // Verifica si $documentoId existe como clave en el array $this->nota
        if (array_key_exists($documentoId, $this->notaevidencias)) {
            // Obtén el valor actualizado de la nota
            $notaActualizadas = $value;
        } else {
            // Maneja el caso en el que $documentoId no existe en el array
            // Puedes agregar alguna lógica adicional según tus necesidades
            $notaActualizadas = '';
        }

        // Busca y actualiza el registro existente para el documento y el cumplimiento
        $registroExistente = cumplimientoEvidencias::where('cumplimiento_id', $this->idcumplimiento->id)->first();

        if ($registroExistente) {
            // Si el registro existe, actualízalo
            $registroExistente->update([
                'nota' => $notaActualizadas,
            ]);
        } else {
        }
    }


    public function ActualizarCumplimiento($id)
    {
        $datosExpedientes = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpedientes) {
            $cumplimientos = cumplimiento::where('expediente_digital_id', $datosExpedientes->id)->first();

            if ($cumplimientos) {
                // Si el registro existe, actualízalo
                $cumplimientos->update([
                    'status_cumplimiento' => 2,
                ]);
            }
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }
    public function catalogoAceptado()
    {
        $this->ctg_aceptado = ctg_aceptado::all();
    }
    public function catalogoRechazado()
    {
        $this->ctg_rechazo = ctg_rechazo::all();
    }
    public function setAceptado($id)
    {
        // Establece el valor de aceptado según el ID del motivo de rechazo seleccionado
        $this->aceptado = $id;
    }
    
    #[On('valida-rechazo')]
    public function negadaValida()
    {
        $datosExpediente = expediente_digital::where('cliente_id', $this->idexpedientedig)->first();

        if ($datosExpediente) {
            $this->idcumplimiento = cumplimiento::where('expediente_digital_id', $datosExpediente->id)->first();
            $cumpliRechazado = cumplimiento_rechazo::where('cumplimiento_id', $this->idcumplimiento->id)->first();
            if (!$cumpliRechazado) {
                cumplimiento_rechazo::create([
                    'cumplimiento_id' =>  $this->idcumplimiento->id,
                    'ctg_rechazo_id' => $this->aceptado,
                    'status_cumplimiento_rechazo' => 1,
                ]);
                $this->idcumplimiento->update([
                    'status_cumplimiento' => 3,
                    'dictamen' => 0,
                    'fecha_dictamen' => now(),
                ]);
            } else {
                $cumpliRechazado->update([
                    'ctg_rechazo_id' => $this->aceptado,
                ]);
                $this->idcumplimiento->update([
                    'status_cumplimiento' => 3,
                    'dictamen' => 0,
                    'fecha_dictamen' => now(),
                ]);
            }
            $datosExpediente->status_expediente_digital = 1;
            $datosExpediente->save();
        }
        return redirect()->route('cumplimiento.index');
    }
    
    #[On('valida-aceptada')]
    public function aceptadaValida()
    {
        $datosExpediente = expediente_digital::where('cliente_id', $this->idexpedientedig)->first();

        if ($datosExpediente) {
            $this->idcumplimiento = cumplimiento::where('expediente_digital_id', $datosExpediente->id)->first();
            $cumpliAceptado = cumplimiento_aceptado::where('cumplimiento_id', $this->idcumplimiento->id)->first();

            if (!$cumpliAceptado) {
                foreach ($this->aceptadoValid as $id => $valor) {
                    cumplimiento_aceptado::create([
                        'cumplimiento_id' => $this->idcumplimiento->id,
                        'ctg_aceptado_id' => $id,
                        'status_cumplimiento_aceptado' => $valor,
                    ]);
                }
                $this->idcumplimiento->update([
                    'status_cumplimiento' => 3,
                    'dictamen' => 1,
                    'fecha_dictamen' => now(),
                ]);
            } else {
                foreach ($this->aceptadoValid as $id => $valor) {
                    $cumpliAceptado->where('ctg_aceptado_id', $id)
                        ->where('cumplimiento_id', $this->idcumplimiento->id)
                        ->update([
                            'status_cumplimiento_aceptado' => $valor,
                        ]);
                }
                $this->idcumplimiento->update([
                    'status_cumplimiento' => 3,
                    'dictamen' => 1,
                    'fecha_dictamen' => now(),
                ]);
            }

            $datosExpediente->cumplimiento = 2;
            $datosExpediente->save();

            if ($datosExpediente->juridico == 2) {
                $datosExpediente->status_expediente_digital = 3;
                $datosExpediente->save();
            }

            if ($datosExpediente->juridico == 2 && $datosExpediente->cumplimiento = 2) {
                Cotizacion::where('cliente_id', $datosExpediente->cliente_id)->update(['status_cotizacion' => 3]);
            }
        }
        return redirect()->route('cumplimiento.index');
    }
    public function obtenerCantidadValidados($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();
        $consulta = DB::table('expediente_documentos as exp')
            ->leftJoin('cumplimiento_doc_validacion as val', 'val.expediente_documentos_id', '=', 'exp.id')
            ->select('val.cumple')
            ->where('exp.expediente_digital_id', $datosExpediente->id)
            ->get();
        $consulta2 = DB::table('expediente_documentos_benf as exp')
            ->leftJoin('cumplimiento_doc_validacion_beneficiario as val', 'val.expediente_documentos_benf_id', '=', 'exp.id')
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
            $this->idcumplimiento = cumplimiento::where('expediente_digital_id', $datosExpediente->id)->first();
        } else {
            // Manejo de error si no se encuentra el expediente_digital
        }
    }
    public function cargarDeValidacionBene($id)
    {
        $datosExpediente = expediente_digital::where('cliente_id', $id)->first();

        if ($datosExpediente) {
            $this->valornotabene = DB::table('cumplimiento as c')
                ->join('cumplimiento_doc_validacion_beneficiario as val', 'c.id', '=', 'val.cumplimiento_id')
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
        $registroExistente = validacioncumplimientobene::where('cumplimiento_id', $this->idcumplimiento->id)
            ->where('expediente_documentos_benf_id', $documentoId)
            ->first();

        if ($registroExistente) {
            // Si el registro existe, actualízalo
            $registroExistente->update([
                'cumple' => $value,
            ]);
        } else {
            // Si el registro no existe, créalo
            validacioncumplimientobene::create([
                'cumplimiento_id' => $this->idcumplimiento->id,
                'expediente_documentos_benf_id' => $documentoId,
                'cumple' => $value,
                'status_validacion_doc_cumplimiento_beneficiario' => 1,
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
        $registroExistente = validacioncumplimientobene::where('cumplimiento_id', $this->idcumplimiento->id)
            ->where('expediente_documentos_benf_id', $documentoId)
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
                'expediente_documentos_benf_id' => $documentoId,
                'cumple' => 0, // Ajusta según tus necesidades
                'nota' => $notaActualizada,
                'status_validacion_doc_cumplimiento_beneficiario' => 1,
            ]);
        }
        $this->obtenerCantidadValidados($this->idexpedientedig);
    }
}
