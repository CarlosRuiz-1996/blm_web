<?php

namespace App\Livewire\Expediente;


use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Ctg_Cp;
use App\Models\ctg_documentos;
use App\Models\ctg_documentos_beneficiarios;
use App\Models\Ctg_Estado;
use App\Models\Ctg_Municipio;
use App\Models\ctg_precio_servicio;
use App\Models\ctg_servicios;
use App\Models\Ctg_Tipo_Cliente;
use App\Models\cumplimiento;
use App\Models\expediente_digital;
use App\Models\expediente_documento_beneficiario;
use App\Models\expediente_documentos;
use App\Models\juridico;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class GestionDocumentos extends Component
{


    public $tuVariable;
    public $documentos;
    public $documentos_beneficiarios;
    public $documentoid;
    public $documentoSelec;
    public $documentoidbene;
    public $documentoSelecbene;
    public $servicioId;
    public $nombreServicio;
    public $tipoServicio;
    public $unidadMedida;
    public $precioUnitario;
    public $editarPrecio;
    public $cantidad = 1;
    public $isAdmin;
    public $total;
    public $estados;
    public $municipios;
    public $cp;
    public $colonias;
    public $cp_invalido;
    public $ctg_cp_id = "";
    public $puesto = "";
    public $nombreContacto = "";
    public $razonSocial;
    public $rfc;
    public $ctg_tipo_cliente_id;
    public $tipoClientelist;
    public $calleNumero;
    public $telefono;
    public $correoElectronico;
    public $vigencia;
    public $condicionpago;
    public $condicionpagolist;
    public $servicios;
    public $precio_servicio;
    public $totalreal;
    public $datoscliente;
    public $pais = "México";
    public $nombretipocliente;
    public $listcliente;
    public $listuser;
    public $tipocliente;
    public $iduser;
    public $idcodpostal;
    public $listcp;
    public $colonia;
    public $listestado;
    public $listmunicipio;
    public $id;
    public $valoridcoti;
    public $valoriidser;
    use WithFileUploads;
    public $checkbeneficiario;
    public $habilitados;
    public $documentosexpediente;
    public $documentosexpedienteBene;
    public $pdfUrl;
    public $isOpen = false;
    public $cliente_sts;

    //obtiene informacion del cliente al cargar la pagina
    public function mount(Request $request)
    {

        $this->cliente_sts = $request->route('sts');
        $id = $request->route('id');

        $this->id = $id;
        $this->datoscliente = Cliente::where('id', $id)->get();

        $this->listcliente = Ctg_Tipo_Cliente::where('id', $this->datoscliente[0]->ctg_tipo_cliente_id)->get();
        $this->ctg_tipo_cliente_id = $this->listcliente[0]->id;
        $this->tipocliente = $this->listcliente[0]->name;
        $this->telefono = $this->datoscliente[0]->phone;
        $this->rfc = $this->datoscliente[0]->rfc_cliente;
        $this->razonSocial = $this->datoscliente[0]->razon_social;
        $this->idcodpostal = $this->datoscliente[0]->ctg_cp_id;
        $this->listcp = Ctg_Cp::where('id', $this->idcodpostal)->get();
        $this->colonia = $this->listcp[0]->colonia;
        $this->cp = $this->listcp[0]->cp;

        $this->listestado = Ctg_Estado::where('id', $this->listcp[0]->ctg_estado_id)->get();
        $this->listmunicipio = Ctg_Municipio::where('id', $this->listcp[0]->ctg_municipio_id)->get();
        $this->estados = $this->listestado[0]->name;
        $this->municipios = $this->listmunicipio[0]->municipio;

        $this->puesto = $this->datoscliente[0]->puesto;
        $this->calleNumero = $this->datoscliente[0]->direccion;
        $this->iduser = $this->datoscliente[0]->user_id;
        $this->listuser = User::where('id', $this->iduser)->get();
        $this->correoElectronico = $this->listuser[0]->email;
        $this->nombreContacto = $this->listuser[0]->name . ' ' . $this->listuser[0]->paterno . ' ' . $this->listuser[0]->materno;
        $this->totalreal = 0;
        $this->documentos = ctg_documentos::where('ctg_tipo_cliente_id', $this->datoscliente[0]->ctg_tipo_cliente_id)->get();
        $this->documentos_beneficiarios = ctg_documentos_beneficiarios::all();
        $this->checkbeneficiario = false;
        $this->habilitados = true;
        $this->cargarDocumentosExpediente();
        $this->cargarDocumentosExpedienteBene();
    }
    //renderiza el componente
    public function render()
    {
        return view('livewire.expediente.gestion-documentos');
    }
    public function cargarDocumentosExpediente()
    {
        // Obtener los documentos del expediente digital actualizado del titular
        $this->documentosexpediente = DB::table('expediente_digital as exp')
            ->join('expediente_documentos as ex', 'ex.expediente_digital_id', '=', 'exp.id')
            ->where('exp.cliente_id', $this->id)
            ->select('ex.ctg_documentos_id', 'ex.document_name', 'ex.id')
            ->get();
    }

    //obtiene registros de documentos de benedificiarios
    public function cargarDocumentosExpedienteBene()
    {
        // Obtener los documentos del expediente digital actualizado de beneficiarios
        $this->documentosexpedienteBene = DB::table('expediente_digital as exp')
            ->join('expediente_documentos_benf as ex', 'ex.expediente_digital_id', '=', 'exp.id')
            ->where('exp.cliente_id', $this->id)
            ->select('ex.ctg_documentos_benf_id', 'ex.document_name', 'ex.id')
            ->get();
    }

    //funcion para cargadr docuemntos
    public function agregarArchivo()
    {
        $this->validate([
            'documentoSelec' => 'required|file|mimes:pdf|max:46080', // 45mb
        ]);

        try {

            $nombreDocumento = ctg_documentos::where('id', $this->documentoid)->first();
            $nombreLimpio = preg_replace('/[^\p{L}\p{N}]+/u', '_', $nombreDocumento->name);

            DB::transaction(function () use ($nombreLimpio) {
                $this->documentoSelec->storeAs(path: 'documentos/' . $this->rfc, name: $nombreLimpio . '.pdf');

                // Verificar si ya existe un expediente digital para el cliente
                $expedienteDigital = expediente_digital::where('cliente_id', $this->id)->first();

                if (!$expedienteDigital) {
                    // No hay un expediente digital para este cliente, realiza el insert
                    $expedienteDigital = expediente_digital::create([
                        'cumplimiento' => 1,
                        'juridico' => 1,
                        'fecha_solicitud' => now(), // Puedes ajustar la fecha según tus necesidades
                        'cliente_id' => $this->id,
                        'status_expediente_digital' => 1,
                    ]);
                }

                //actualizar cotizaciones en 2 
                $cliente = Cliente::find($expedienteDigital->cliente_id);
                if ($cliente->status_cliente == 0) {
                    Cotizacion::where('cliente_id', $cliente->id)->where('status_cotizacion', 1)->update(['status_cotizacion' => 2]);
                }



                // Verificar si ya existe un registro con el mismo ctg_documento_id y expediente_digital
                $documentoExistente = expediente_documentos::where('ctg_documentos_id', $this->documentoid)
                    ->where('expediente_digital_id', $expedienteDigital->id)
                    ->first();

                if ($documentoExistente) {
                    // El documento ya existe, realiza la actualización
                    $documentoExistente->update([
                        'document_name' => $nombreLimpio . '.pdf',
                        'status_expediente_doc' => 1,
                    ]);
                } else {
                    // El documento no existe, realiza la inserción
                    expediente_documentos::create([
                        'expediente_digital_id' => $expedienteDigital->id,
                        'ctg_documentos_id' => $this->documentoid,
                        'document_name' => $nombreLimpio . '.pdf',
                        'status_expediente_doc' => 1,
                    ]);
                }




                $this->cargarDocumentosExpediente();
            });
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se ha agregado el archivo: ' . $nombreLimpio . '.pdf'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Fallo al subir archivo'], ['tipomensaje' => 'error']);
        }
    }

    //funcion para agregra archivos a beneficiario
    public function agregarArchivoBeneficiario()
    {
        $this->validate([
            'documentoSelecbene' => 'required|file|mimes:pdf|max:10240', // Ajusta según tus necesidades
        ]);
        try {

            $nombredoc = ctg_documentos_beneficiarios::where('id', $this->documentoidbene)->first();
            $nombreLimpio = preg_replace('/[^\p{L}\p{N}]+/u', '_', $nombredoc->name);
            DB::transaction(function () use ($nombreLimpio) {

                $this->documentoSelecbene->storeAs(path: 'documentos/' . $this->rfc . '/beneficiario', name: $nombreLimpio . '.pdf');

                $expedienteDigital = expediente_digital::where('cliente_id', $this->id)->first();


                if (!$expedienteDigital) {
                    // No hay un expediente digital para este cliente, realiza el insert
                    $expedienteDigital = expediente_digital::create([
                        'cumplimiento' => 1,
                        'juridico' => 1,
                        'fecha_solicitud' => now(), // Puedes ajustar la fecha según tus necesidades
                        'cliente_id' => $this->id,
                        'status_expediente_digital' => 1,
                    ]);
                }
                $cliente = Cliente::find($expedienteDigital->cliente_id);
                if ($cliente->status_cliente == 0) {
                    Cotizacion::where('cliente_id', $cliente->id)->where('status_cotizacion', 1)->update(['status_cotizacion' => 2]);
                }
                $documentoExistente = expediente_documento_beneficiario::where('ctg_documentos_benf_id', $this->documentoidbene)
                    ->where('expediente_digital_id', $expedienteDigital->id)
                    ->first();

                if ($documentoExistente) {
                    // El documento ya existe, realiza la actualización
                    $documentoExistente->update([
                        'document_name' => $nombreLimpio . '.pdf',
                        'status_expediente_doc' => 1,
                    ]);
                } else {
                    // El documento no existe, realiza la inserción
                    expediente_documento_beneficiario::create([
                        'expediente_digital_id' => $expedienteDigital->id,
                        'ctg_documentos_benf_id' => $this->documentoidbene,
                        'document_name' => $nombreLimpio . '.pdf',
                        'status_expediente_doc_benf' => 1,
                    ]);
                }
                $this->cargarDocumentosExpedienteBene();
            });
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se ha agregado el archivo: ' . $nombreLimpio . '.pdf'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Fallo al subir archivo'], ['tipomensaje' => 'error']);
        }
    }
    public function updatedcheckbeneficiario()
    {

        if ($this->checkbeneficiario != true) {
            $this->habilitados = true;
        } else {
            $this->habilitados = false;
        }
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

    //funcion paraeliminar documentos 
    public function eliminarArchivo($iddocumento)
    {
        // Encuentra el documento por su ID
        try {
            $documento = expediente_documentos::find($iddocumento);
            $nombreElimninado = $documento->document_name;
            if ($documento) {
                $rutaArchivo = 'documentos/' . $this->rfc . '/' . $documento->document_name;
                // Elimina el archivo del sistema de archivos
                Storage::delete($rutaArchivo);
                $documento->delete();
                $this->cargarDocumentosExpediente();
            }
            $this->dispatch('ArchivoEliminado', ['nombreArchivo' => 'Se ha Eliminado el archivo: ' . $nombreElimninado], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('ArchivoEliminado', ['nombreArchivo' => 'Fallo al intentar eliminar archivo'], ['tipomensaje' => 'error']);
        }
    }
     //funcion para eliminar documentos de beneficiario 
    public function eliminarArchivoBene($iddocumento)
    {
        // Encuentra el documento por su ID
        try {
            $documento = expediente_documento_beneficiario::find($iddocumento);
            $nombreElimninado = $documento->document_name;
            if ($documento) {
                $rutaArchivo = 'documentos/' . $this->rfc . '/beneficiario/' . $documento->document_name;
                // Elimina el archivo del sistema de archivos
                Storage::delete($rutaArchivo);
                $documento->delete();
                $this->cargarDocumentosExpedienteBene();
            }
            $this->dispatch('ArchivoEliminado', ['nombreArchivo' => 'Se ha Eliminado el archivo: ' . $nombreElimninado], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('ArchivoEliminado', ['nombreArchivo' => 'Fallo al intentar eliminar archivo'], ['tipomensaje' => 'error']);
        }
    }


    /**finalizar expediente. */
    #[On('finalizar-expediente')]
    public function finalizaExpediente()
    {
        $expedienteDigital = expediente_digital::where('cliente_id', $this->id)->first();
        //actualiza los status
        $documentos =  expediente_documentos::where('expediente_digital_id', $expedienteDigital->id)->count();
        $benef =  expediente_documento_beneficiario::where('expediente_digital_id', $expedienteDigital->id)->count();
        $ctg = ctg_documentos::where('ctg_tipo_cliente_id', $this->datoscliente[0]->ctg_tipo_cliente_id)->count();


        // dd($benef);
        if ($ctg == $documentos && ($benef == 0 || $benef == 3)) {

            $expedienteDigital->status_expediente_digital = 2;
            $expedienteDigital->save();
            cumplimiento::create([
                'expediente_digital_id' =>  $expedienteDigital->id,
                'dictamen' => 0,
                'fecha_dictamen' => now(), // Puedes ajustar la fecha según tus necesidades
                'status_cumplimiento' => 1,

            ]);
            juridico::create([
                'expediente_digital_id' =>  $expedienteDigital->id,
                'dictamen' => 0,
                'fecha_dictamen' => now(), // Puedes ajustar la fecha según tus necesidades
                'status_juridico' => 1,

            ]);
            
            $this->dispatch('success',['Expediente generado con exito',$this->datoscliente[0]]);
        }else{
            $this->dispatch('error');
        }
    }
}
