<?php

namespace App\Livewire;

use App\Models\Cliente;
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
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AltaSolicitudCumplimiento extends Component
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



    public function mount(Request $request)
    {
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
        $this->documentos = ctg_documentos::where('ctg_tipo_cliente_id', 1)->get();
        $this->documentos_beneficiarios = ctg_documentos_beneficiarios::all();
        $this->checkbeneficiario = false;
        $this->habilitados = true;
        $this->cargarDocumentosExpediente();
        $this->cargarDocumentosExpedienteBene();
    }
    public function render()
    {
        return view('livewire.alta-solicitud-cumplimiento');
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


    public function agregarArchivo()
    {
        $this->validate([
            'documentoSelec' => 'required|file|mimes:pdf|max:10240', // Ajusta según tus necesidades
        ]);

        $nombre = ctg_documentos::where('id', $this->documentoid)->first();
        DB::transaction(function () use ($nombre) {
        // Almacenar el archivo en la carpeta especificada y obtener la ruta
        $nombreLimpio = preg_replace('/[^\p{L}\p{N}]+/u', '_', $nombre->name);
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
            cumplimiento::create([
                'expediente_digital_id' =>  $expedienteDigital->id,
                'dictamen' => 0,
                'fecha_dictamen' => now(), // Puedes ajustar la fecha según tus necesidades
                'status_cumplimiento' => 1,
    
            ]);
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
    }
    public function agregarArchivoBeneficiario()
    {
        $this->validate([
            'documentoSelecbene' => 'required|file|mimes:pdf|max:10240', // Ajusta según tus necesidades
        ]);

        $nombredoc = ctg_documentos_beneficiarios::where('id', $this->documentoidbene)->first();
        DB::transaction(function () use ($nombredoc) {
        $nombreLimpio = preg_replace('/[^\p{L}\p{N}]+/u', '_', $nombredoc->name);
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
            cumplimiento::create([
                'expediente_digital_id' =>  $expedienteDigital->id,
                'dictamen' => 0,
                'fecha_dictamen' => null, // Puedes ajustar la fecha según tus necesidades
                'status_cumplimiento' => 1,

            ]);
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
}
