<?php

namespace App\Livewire\Juridico;

use App\Models\Cliente;
use App\Models\Contratos_cotizacion;
use App\Models\Cotizacion;
use App\Models\cotizacion_servicio;
use App\Models\CotizacionServicio;
use App\Models\Ctg_Contratos;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Altacontratos extends Component
{



    use WithPagination, WithoutUrlPagination;
    public $clienteslis = [];
    public $clientenombre;
    protected $listeners = ['resetPagination'];
    public $contratos;
    public $cliente;
    public $ctg_tipocontrato;
    //formulario
    public $razonSocial;
    public $rfc;
    public $tipocliente;
    public $nombreContacto;
    public $puesto;
    public $apoderado;
    public $escritura;
    public $licenciado;
    public $foliomercantil;
    public $fecharegistro;
    public $lugarregistro;
    public $notario;
    public $datosextra;
    public $numnotario;
    public $fechapodernotarial;
    public $ciudadnotaria;
    public $id;
    public $camposextra;
    public $cotizaciones;
    public $seleccioncotiza;
    public $contratosList;
    public $camposextraeditar;
    public $apoderadoEditar;
    public $escrituraEditar;
    public $licenciadoEditar;
    public $foliomercantilEditar;
    public $fecharegistroEditar;
    public $lugarregistroEditar;
    public $notarioEditar;
    public $datosextraEditar;
    public $numnotarioEditar;
    public $fechapodernotarialEditar;
    public $ciudadnotariaEditar;
    public $idEditar;
    public $imageKey;
    public $fileName;
    public $docword;
    public $idcontratoEditar;
    public $buscarRealizado=false;
    use WithFileUploads;
    public function updatedDocword()
    {
        // Aquí puedes acceder al nombre del archivo
        $fileName = $this->docword->getClientOriginalName();
        
        // Puedes almacenar el nombre en una propiedad para mostrarlo en la vista
        $this->fileName = $fileName;
    }
    public function mount()
    {
        $this->camposextraeditar = false;
        $this->camposextra = false;
        $this->contratos = Ctg_Contratos::all();
        $this->clientenombre = "";
        $this->cotizaciones = [];
        $this->contratosList = [];
        $this->buscarRealizado=false;
    }

    public function render()
    {
        $data = $this->BuscarCliente();
        return view('livewire.juridico.altacontratos', compact('data'));
    }

    public function resetPagination()
    {
        // Reiniciar la paginación cuando sea necesario desde fuera del componente
        $this->resetPage();
    }
    

    public function BuscarCliente()
    {
        $this->buscarRealizado=false;
        $valorbuscado = $this->clientenombre;

        if ($valorbuscado == "" || $valorbuscado == null) {
            $this->buscarRealizado=false;
            $busqueda = []; // Paginar antes de obtener los resultados
        } else {
            $this->buscarRealizado=false;
            $busqueda = Cliente::select('clientes.id','clientes.rfc_cliente','clientes.user_id', 'clientes.razon_social', 'users.name', 'users.paterno', 'users.materno')
                ->join('users', 'users.id', '=', 'clientes.user_id')
                ->where('clientes.status_cliente', 1)
                ->where(function ($query) use ($valorbuscado) {
                    $query->where('users.name', 'LIKE', '%' . $valorbuscado . '%')
                        ->orWhere('users.paterno', 'LIKE', '%' . $valorbuscado . '%')
                        ->orWhere('users.materno', 'LIKE', '%' . $valorbuscado . '%')
                        ->orWhere('clientes.rfc_cliente', 'LIKE', '%' . $valorbuscado . '%')
                        ->orWhere('clientes.razon_social', 'LIKE', '%' . $valorbuscado . '%');
                })
                ->paginate(10); // Paginar antes de obtener los resultados
                $this->buscarRealizado=true;
                $this->resetPagination();
        }
        
        return $busqueda;
    }


    public function generarpdf()
    {
        
        $this->validate([
            'razonSocial' => 'required',
            'rfc' => 'required',
            'tipocliente' => 'required',
            'nombreContacto' => 'required',
            'seleccioncotiza' => 'required',
            'puesto' => 'required',
            'apoderado' => 'required',
            'escritura' => 'required',
            'licenciado' => 'required',
            'foliomercantil' => 'required',
            'fecharegistro' => 'required',
            'lugarregistro' => 'required',
            'ctg_tipocontrato' => 'required',
            'notario' => 'required',
            'numnotario' => 'required_if:datosextra,true',
            'fechapodernotarial' => 'required_if:datosextra,true',
            'ciudadnotaria' => 'required_if:datosextra,true',
        ]);
        DB::beginTransaction();
        try {
            $contratoBusqueda = Ctg_Contratos::find($this->ctg_tipocontrato);
            $cotizacionser = CotizacionServicio::where('cotizacion_id', $this->seleccioncotiza)->get();
            $contratosCotizacion = Contratos_cotizacion::where('cotizacion_id', $this->seleccioncotiza)->where('ctg_contratos_id', $this->ctg_tipocontrato)->where('cliente_id', $this->id)->get();
            $cliente = Cliente::find($this->id);
            try {
                if ($contratoBusqueda) {
                    $nombre_doc = $contratoBusqueda->path;
                } else {
                    throw new \Exception('No se encontró ningún contrato para la búsqueda especificada.');
                }
            } catch (\Exception $e) {
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'No se encontró ningún contrato para la búsqueda especificada'], ['tipomensaje' => 'warning']);
            }
            if ($contratosCotizacion->isNotEmpty()) {
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Ya existe un contrato de este tipo para esta cotizacion'], ['tipomensaje' => 'warning']);
            } else {
                Contratos_cotizacion::create([
                    'cotizacion_id' => $this->seleccioncotiza,
                    'status_contrato' => 1,
                    'ctg_contratos_id' => $this->ctg_tipocontrato,
                    'apoderado' => $this->apoderado,
                    'escritura' => $this->escritura,
                    'licenciado' => $this->licenciado,
                    'foliomercantil' => $this->foliomercantil,
                    'fecharegistro' => $this->fecharegistro,
                    'lugarregistro' => $this->lugarregistro,
                    'notario' => $this->notario,
                    'numnotario' => $this->numnotario,
                    'fechapodernotarial' => $this->fechapodernotarial,
                    'ciudadnotaria' => $this->ciudadnotaria,
                    'cliente_id' => $this->id,
                    'status_editado' => 0,
                ]);
            $this->contratosList = Contratos_cotizacion::where('cliente_id',  $this->id)->get();
            $template = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/contratos/' . $nombre_doc));
            //table1
            $table = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
            $cellRowSpan = array('vMerge' => 'restart');
            $cellRowContinue = array('vMerge' => 'continue');
            $cellColSpan = array('gridSpan' => 3);
            $cellColSpan3 = array('gridSpan' => 4);
            $cellColSpan2 = array('gridSpan' => 4, 'bgColor' => '#808080');
            $estiloCelda = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
            $table->addRow();
            $table->addCell(2000, $cellColSpan2)->addText('ANEXO 1', null, array('align' => 'center', 'bold' => true));
            $table->addRow();
            $table->addCell(2000, $cellColSpan2)->addText('');
            $table->addRow();
            $table->addCell(2000, $cellColSpan3)->addText('');
            $table->addRow();
            $table->addCell(150)->addText('CLIENTE', null, $estiloCelda);
            $table->addCell(150)->addText($cliente->razon_social);
            $table->addCell(150)->addText('CLAVE CLIENTE', null, $estiloCelda);
            $table->addCell(150)->addText($cliente->id);
            $table->addRow();
            $table->addCell(150)->addText('RFC', null, $estiloCelda);
            $table->addCell(150)->addText($cliente->rfc_cliente);
            $table->addCell(150)->addText('FECHA', null, $estiloCelda);
            $table->addCell(150)->addText(date('d-m-y'));
            $table->addRow();
            $table->addCell(150)->addText('DIRECCIÓN', null, $estiloCelda);
            $table->addCell(2000, $cellColSpan)->addText($cliente->direccion . ' ' . $cliente->cp->colonia . ' ' . $cliente->cp->cp . ' ' . $cliente->cp->municipio->municipio . ' ');
            $table->addRow();
            $table->addCell(150)->addText('FACTURADO A', null, $estiloCelda);
            $table->addCell(2000, $cellColSpan)->addText($cliente->razon_social);

            //table2
            //tabla anexo parte 2

            $table2 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
            $cellRowSpan1 = array('vMerge' => 'restart');
            $cellRowContinue2 = array('vMerge' => 'continue');
            $cellColSpan1 = array('gridSpan' => 3);
            $cellColSpan31 = array('gridSpan' => 4);
            $cellColSpan21 = array('gridSpan' => 4, 'bgColor' => '#808080');
            $estiloCelda1 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
            $table2->addRow();
            $table2->addCell(2000, $cellColSpan21)->addText('CONTACTO', null, array('align' => 'center', 'bold' => true));
            $table2->addCell(2000, $cellColSpan21)->addText('CARGO', null, array('align' => 'center', 'bold' => true));
            $table2->addCell(2000, $cellColSpan21)->addText('TELEFONO', null, array('align' => 'center', 'bold' => true));
            $table2->addRow();
            $table2->addCell(2000, $cellColSpan31)->addText($cliente->user->name . ' ' . $cliente->user->paterno . ' ' . $cliente->user->materno);
            $table2->addCell(2000, $cellColSpan31)->addText($cliente->puesto);
            $table2->addCell(2000, $cellColSpan31)->addText($cliente->phone);

            //tabla3
            $table3 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
            $cellRowSpan12 = array('vMerge' => 'restart');
            $cellRowContinue3 = array('vMerge' => 'continue');
            $cellColSpan122 = array('gridSpan' => 2);
            $cellColSpan12 = array('gridSpan' => 3);
            $cellColSpan312 = array('gridSpan' => 4);
            $cellColSpan212 = array('gridSpan' => 4, 'bgColor' => '#808080');
            $estiloCelda12 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
            $total=0;
            if ($cotizacionser) {
                foreach ($cotizacionser as $cotizacion) {
                    $table3->addRow();
                    $table3->addCell(2000, $cellColSpan212)->addText('CANTIDAD', null, array('align' => 'center', 'bold' => true));
                    $table3->addCell(2000, $cellColSpan212)->addText('CONCEPTO', null, array('align' => 'center', 'bold' => true));
                    $table3->addCell(2000, $cellColSpan212)->addText('PRECIO POR SEVICIO', null, array('align' => 'center', 'bold' => true));
                    $table3->addCell(2000, $cellColSpan212)->addText('IMPORTE', null, array('align' => 'center', 'bold' => true));
                    $table3->addRow();
                    $table3->addCell(2000, $cellColSpan312)->addText($cotizacion->servicio->cantidad);
                    $table3->addCell(2000, $cellColSpan312)->addText(strtoupper($cotizacion->servicio->ctg_servicio->descripcion));
                    $table3->addCell(2000, $cellColSpan312)->addText($cotizacion->servicio->precio_unitario);
                    $table3->addCell(2000, $cellColSpan312)->addText($cotizacion->servicio->subtotal);
                    $total+=$cotizacion->servicio->subtotal;
                }
            }
            $table3->addRow();
            $table3->addCell(2000, $cellColSpan312)->addText('');
            $table3->addCell(2000, $cellColSpan312)->addText('');
            $table3->addCell(2000, $cellColSpan312)->addText('');
            $table3->addCell(2000, $cellColSpan312)->addText('');
            $table3->addRow();
            $table3->addCell(2000, $cellColSpan312)->addText('');
            $table3->addCell(2000, $cellColSpan312)->addText('PRECIOS MAS 16 % DE I V A');
            $table3->addCell(2000, $cellColSpan312)->addText('');
            $table3->addCell(2000, $cellColSpan312)->addText('$' . '' . number_format($total, 2, '.', ','));
            //table4
            $table4 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
            $cellRowSpan1212 = array('vMerge' => 'restart');
            $cellRowContinue312 = array('vMerge' => 'continue');
            $cellColSpan1212 = array('gridSpan' => 3);
            $cellColSpan3122 = array('gridSpan' => 4);
            $cellColSpan21212 = array('gridSpan' => 4, 'bgColor' => '#808080');
            $estiloCelda1212 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
            $table4->addRow();
            $table4->addCell(2000, $cellColSpan21212)->addText('OBSERVACIONES', null, array('align' => 'center', 'bold' => true));
            $table4->addRow();
            $table4->addCell(2000, $cellColSpan3122)->addText('', null, array('align' => 'center'));
            $table4->addRow();
            $table4->addCell(2000, $cellColSpan3122)->addText('');
            $table4->addRow();
            $table4->addCell(2000, $cellColSpan3122)->addText('LOS SERVICIOS NO ESPECIFICADOS EN ESTA COTIZACIÓN SE COBRARAN A PRECIOS DE LISTA', null, array('align' => 'center', 'bold' => true));
            //tabla5
            $table5 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
            $cellRowSpan1212 = array('vMerge' => 'restart');
            $cellRowContinue312 = array('vMerge' => 'continue');
            $cellColSpan1212 = array('gridSpan' => 3);
            $cellColSpan3122 = array('gridSpan' => 4);
            $cellColSpan21212 = array('gridSpan' => 4, 'bgColor' => '#808080');
            $estiloCelda1212 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
            $table5->addRow();
            $table5->addCell(2000, $cellColSpan21212)->addText('POR LA COMPAÑIA', null, array('align' => 'center', 'bold' => true));
            $table5->addCell(2000, $cellColSpan21212)->addText('POR EL CLIENTE', null, array('align' => 'center', 'bold' => true));
            $table5->addRow();
            $table5->addCell(2000, $cellColSpan3122)->addText('SILVESTRE OCTAVIANO GARCIA CARRILLO');
            $table5->addCell(2000, $cellColSpan3122)->addText($cliente->user->name . ' ' . $cliente->user->paterno . ' ' . $cliente->user->materno);
            $table5->addRow();
            $table5->addCell(2000, $cellColSpan3122)->addText('SERVICIOS INTEGRADOS PRO-BLM DE MEXICO, S.A. DE C.V.');
            $table5->addCell(2000, $cellColSpan3122)->addText($cliente->razon_social);
            $template->setComplexBlock('table', $table);
            $template->setComplexBlock('table2', $table2);
            $template->setComplexBlock('table4', $table4);
            $template->setComplexBlock('table3', $table3);
            $template->setComplexBlock('table5', $table5);
            $template->setValue('razonsocial', $this->razonSocial);
            $template->setValue('nombre', $this->nombreContacto);
            $template->setValue('apoderada', $this->apoderado);
            $template->setValue('escritura', $this->escritura);
            $template->setValue('notario', $this->notario);
            $template->setValue('licenciado', $this->licenciado);
            $template->setValue('folio_mercantil', $this->foliomercantil);
            $template->setValue('fecha_reg', $this->fecharegistro);
            $template->setValue('lugar_registro', $this->lugarregistro);
            $template->setValue('calle', $cliente->direccion);
            $template->setValue('noInterior', $cliente->direccion);
            $template->setValue('noExterior', $cliente->direccion);
            $template->setValue('colonia', $cliente->cp->colonia);
            $template->setValue('municipio', $cliente->cp->municipio->municipio);
            $template->setValue('cp', $cliente->cp->cp);
            $template->setValue('rfccliente', $this->rfc);
            $template->setValue('fecha_reg_formt', $this->fecharegistro);
            $tenpFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tenpFile);

            $header = [
                "Content-Type: application/octet-stream",
            ];
            DB::commit();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se creo contrato y se ha descargo'], ['tipomensaje' => 'success']);
            return response()->download($tenpFile, $this->rfc . '_' . $nombre_doc, $header)->deleteFileAfterSend($shouldDelete = true);
            } 
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se produjo un error al procesar el archivo'], ['tipomensaje' => 'danger']);
            return back()->with('error', 'Se produjo un error al procesar el archivo: ' . $e->getMessage());
        }
    }


    public function mostrarCliente($clienteId)
    {
        $this->cliente = Cliente::find($clienteId);
        $this->id = $this->cliente->id;
        $this->razonSocial = $this->cliente->razon_social;
        $this->rfc = $this->cliente->rfc_cliente;
        $this->tipocliente = $this->cliente->tipo_cliente->name;
        $this->nombreContacto = $this->cliente->user->name . ' ' . $this->cliente->user->paterno . ' ' . $this->cliente->user->materno;
        $this->puesto = $this->cliente->puesto;
        $this->cotizaciones = $this->cotizaciones = Cotizacion::where('status_cotizacion', 5)
            ->where('cliente_id', $this->id)
            ->get();
        $this->contratosList = Contratos_cotizacion::where('cliente_id', $this->id)->get();
        $this->dispatch('cerrarModal');
        $this->dispatch('cerrarModal', [$this->nombreContacto]);
    }

    public function updatedDatosextra()
    {
        if (!$this->datosextra) {
            $this->camposextra = false;
        } else {
            $this->camposextra = true;
        }
    }
    public function editarContrato($id)
    {
        $contratosCotizacion = Contratos_cotizacion::find($id);
        $this->idEditar = $id;
        $this->apoderadoEditar = $contratosCotizacion->apoderado;
        $this->escrituraEditar = $contratosCotizacion->escritura;
        $this->licenciadoEditar = $contratosCotizacion->licenciado;
        $this->foliomercantilEditar = $contratosCotizacion->foliomercantil;
        $this->fecharegistroEditar = Carbon::parse($contratosCotizacion->fecharegistro)->format('Y-m-d');
        $this->lugarregistroEditar = $contratosCotizacion->lugarregistro;
        $this->notarioEditar = $contratosCotizacion->notario;
        if ($contratosCotizacion->numnotario  || ($contratosCotizacion->fechapodernotarial && $contratosCotizacion->fechapodernotarial != null) || $contratosCotizacion->ciudadnotaria) {
            if ($contratosCotizacion->fechapodernotarial) {
                $this->fechapodernotarialEditar = Carbon::parse($contratosCotizacion->fechapodernotarial)->format('Y-m-d');
            } else {
                $this->fechapodernotarialEditar = null;
            }
            if ($contratosCotizacion->numnotario) {
                $this->numnotarioEditar =  $contratosCotizacion->numnotario;
            } else {
                $this->numnotarioEditar = "";
            }
            if ($contratosCotizacion->ciudadnotaria) {
                $this->ciudadnotariaEditar =  $contratosCotizacion->ciudadnotaria;
            } else {
                $this->ciudadnotariaEditar = "";
            }
            $this->datosextraEditar = true;
            $this->camposextraeditar = true;
        } else {
            $this->datosextraEditar = false;
            $this->camposextraeditar = false;
        }
        
    }
    public function updatedDatosextraEditar()
    {
        if (!$this->datosextraEditar) {
            $this->camposextraeditar = false;
        } else {
            $this->camposextraeditar = true;
        }
    }

    public function editarContratoAceptar()
    {
        // Iniciamos la transacción de la base de datos
        DB::beginTransaction();

        try {
            // Validamos los datos de entrada
            $this->validate([
                'idEditar' => 'required',
                'apoderadoEditar' => 'required',
                'escrituraEditar' => 'required',
                'licenciadoEditar' => 'required',
                'foliomercantilEditar' => 'required',
                'fecharegistroEditar' => 'required',
                'lugarregistroEditar' => 'required',
                'notarioEditar' => 'required',
                'numnotarioEditar' => 'required_if:datosextraEditar,true',
                'fechapodernotarialEditar' => 'required_if:datosextraEditar,true',
                'ciudadnotariaEditar' => 'required_if:datosextraEditar,true',
            ]);

            // Buscamos el contrato de cotización por su ID
            $contratoCotizacion = Contratos_cotizacion::find($this->idEditar);

            // Verificamos si se encontró el contrato
            if ($contratoCotizacion) {
                // Actualizamos los atributos con los valores de los wire models de edición
                $contratoCotizacion->apoderado = $this->apoderadoEditar;
                $contratoCotizacion->escritura = $this->escrituraEditar;
                $contratoCotizacion->licenciado = $this->licenciadoEditar;
                $contratoCotizacion->foliomercantil = $this->foliomercantilEditar;
                $contratoCotizacion->fecharegistro = $this->fecharegistroEditar;
                $contratoCotizacion->lugarregistro = $this->lugarregistroEditar;
                $contratoCotizacion->notario = $this->notarioEditar;
                $contratoCotizacion->numnotario = $this->numnotarioEditar;
                $contratoCotizacion->fechapodernotarial = $this->fechapodernotarialEditar;
                $contratoCotizacion->ciudadnotaria = $this->ciudadnotariaEditar;
                $contratoCotizacion->save();
                DB::commit();
                $this->contratosList = Contratos_cotizacion::where('cliente_id', $contratoCotizacion->cliente_id)->get();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
    public function ReactivarContrato($id)
    {
        $contratoCotizacion = Contratos_cotizacion::find($id);
        $contratoCotizacion->status_contrato = 1;
        $contratoCotizacion->save();
        $this->contratosList = Contratos_cotizacion::where('cliente_id', $contratoCotizacion->cliente_id)->get();
    }
    public function CancelarContrato($id)
    {
        $contratoCotizacion = Contratos_cotizacion::find($id);
        $contratoCotizacion->status_contrato = 0;
        $contratoCotizacion->save();
        $this->contratosList = Contratos_cotizacion::where('cliente_id', $contratoCotizacion->cliente_id)->get();
    }
    
    public function resubirContrato($id){
        $contratoCotizacion = Contratos_cotizacion::find($id);
        $this->idcontratoEditar=$contratoCotizacion->id;
       
    }
    #[On('guardardocumento')]
    public function subirContrato(){
        $this->validate([
            'idcontratoEditar' => 'required',
            'docword' => 'required|file|mimes:docx',
        ]);
        try {
            DB::beginTransaction();
            $contratoCotizacion = Contratos_cotizacion::find($this->idcontratoEditar);
            $contratoCotizacion->status_editado = 1;
            $contratoCotizacion->save();

            $textoRecibido = $contratoCotizacion->ctg_contratos->path;
            $nombreLimpio = str_replace(' ', '_', $textoRecibido);
            $this->docword->storeAs(path: 'contratos/cotizacion/'.$contratoCotizacion->cliente->id.'/'.$contratoCotizacion->cotizacion_id.'/', name: $nombreLimpio);

            DB::commit();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Se ha agregado el archivo: ' . $nombreLimpio], ['tipomensaje' => 'success']);
            $this->contratosList = Contratos_cotizacion::where('cliente_id',  $contratoCotizacion->cliente->id)->get();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Fallo al subir archivo'], ['tipomensaje' => 'error']);
        }
        $this->idcontratoEditar = '';
        $this->imageKey = rand();
    }

        public function descargarArchivo($contratoId)
        {
            // Encuentra el contrato
            $contratoCotizacion = Contratos_cotizacion::find($contratoId);

            // Verifica si el contrato existe
            if (!$contratoCotizacion) {
                // Manejar caso en que el contrato no existe
                return;
            }

            // Construye la ruta del archivo
            $rutaArchivo = 'contratos/cotizacion/'.$contratoCotizacion->cliente->id.'/'.$contratoCotizacion->cotizacion_id.'/'.$contratoCotizacion->ctg_contratos->path;;

            // Verifica si el archivo existe en el almacenamiento
            if (!Storage::exists($rutaArchivo)) {
                // Manejar caso en que el archivo no existe
                return;
            }

            // Retorna una respuesta de descarga del archivo
            return Storage::download($rutaArchivo);
        }
        public function generarArchivo($id){
            try {
                $contratosCotizacion = Contratos_cotizacion::find($id);
                $template = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/contratos/' . $contratosCotizacion->ctg_contratos->path));
                //table1
                $table = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
                $cellRowSpan = array('vMerge' => 'restart');
                $cellRowContinue = array('vMerge' => 'continue');
                $cellColSpan = array('gridSpan' => 3);
                $cellColSpan3 = array('gridSpan' => 4);
                $cellColSpan2 = array('gridSpan' => 4, 'bgColor' => '#808080');
                $estiloCelda = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
                $table->addRow();
                $table->addCell(2000, $cellColSpan2)->addText('ANEXO 1', null, array('align' => 'center', 'bold' => true));
                $table->addRow();
                $table->addCell(2000, $cellColSpan2)->addText('');
                $table->addRow();
                $table->addCell(2000, $cellColSpan3)->addText('');
                $table->addRow();
                $table->addCell(150)->addText('CLIENTE', null, $estiloCelda);
                $table->addCell(150)->addText($contratosCotizacion->cliente->razon_social);
                $table->addCell(150)->addText('CLAVE CLIENTE', null, $estiloCelda);
                $table->addCell(150)->addText($contratosCotizacion->cliente->id);
                $table->addRow();
                $table->addCell(150)->addText('RFC', null, $estiloCelda);
                $table->addCell(150)->addText($contratosCotizacion->cliente->rfc_cliente);
                $table->addCell(150)->addText('FECHA', null, $estiloCelda);
                $table->addCell(150)->addText(date('d-m-y'));
                $table->addRow();
                $table->addCell(150)->addText('DIRECCIÓN', null, $estiloCelda);
                $table->addCell(2000, $cellColSpan)->addText($contratosCotizacion->cliente->direccion . ' ' . $contratosCotizacion->cliente->cp->colonia . ' ' . $contratosCotizacion->cliente->cp->cp . ' ' . $contratosCotizacion->cliente->cp->municipio->municipio . ' ');
                $table->addRow();
                $table->addCell(150)->addText('FACTURADO A', null, $estiloCelda);
                $table->addCell(2000, $cellColSpan)->addText($contratosCotizacion->cliente->razon_social);
    
                //table2
                //tabla anexo parte 2
    
                $table2 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
                $cellRowSpan1 = array('vMerge' => 'restart');
                $cellRowContinue2 = array('vMerge' => 'continue');
                $cellColSpan1 = array('gridSpan' => 3);
                $cellColSpan31 = array('gridSpan' => 4);
                $cellColSpan21 = array('gridSpan' => 4, 'bgColor' => '#808080');
                $estiloCelda1 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
                $table2->addRow();
                $table2->addCell(2000, $cellColSpan21)->addText('CONTACTO', null, array('align' => 'center', 'bold' => true));
                $table2->addCell(2000, $cellColSpan21)->addText('CARGO', null, array('align' => 'center', 'bold' => true));
                $table2->addCell(2000, $cellColSpan21)->addText('TELEFONO', null, array('align' => 'center', 'bold' => true));
                $table2->addRow();
                $table2->addCell(2000, $cellColSpan31)->addText($contratosCotizacion->cliente->user->name . ' ' . $contratosCotizacion->cliente->user->paterno . ' ' . $contratosCotizacion->cliente->user->materno);
                $table2->addCell(2000, $cellColSpan31)->addText($contratosCotizacion->cliente->puesto);
                $table2->addCell(2000, $cellColSpan31)->addText($contratosCotizacion->cliente->phone);
    
                //tabla3
                $table3 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
                $cellRowSpan12 = array('vMerge' => 'restart');
                $cellRowContinue3 = array('vMerge' => 'continue');
                $cellColSpan122 = array('gridSpan' => 2);
                $cellColSpan12 = array('gridSpan' => 3);
                $cellColSpan312 = array('gridSpan' => 4);
                $cellColSpan212 = array('gridSpan' => 4, 'bgColor' => '#808080');
                $estiloCelda12 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
                $table3->addRow();
                $table3->addCell(2000, $cellColSpan212)->addText('CANTIDAD', null, array('align' => 'center', 'bold' => true));
                $table3->addCell(2000, $cellColSpan212)->addText('CONCEPTO', null, array('align' => 'center', 'bold' => true));
                $table3->addCell(2000, $cellColSpan212)->addText('PRECIO POR SEVICIO', null, array('align' => 'center', 'bold' => true));
                $table3->addCell(2000, $cellColSpan212)->addText('IMPORTE', null, array('align' => 'center', 'bold' => true));
                $total=0;
                if ($contratosCotizacion->cotizacion->cotizacion_servicio) {
                    foreach ($contratosCotizacion->cotizacion->cotizacion_servicio as $cotizacion) {
                        $table3->addRow();
                        $table3->addCell(2000, $cellColSpan312)->addText($cotizacion->servicio->cantidad);
                        $table3->addCell(2000, $cellColSpan312)->addText(strtoupper($cotizacion->servicio->ctg_servicio->descripcion));
                        $table3->addCell(2000, $cellColSpan312)->addText($cotizacion->servicio->precio_unitario);
                        $table3->addCell(2000, $cellColSpan312)->addText($cotizacion->servicio->subtotal);
                        $total+=$cotizacion->servicio->subtotal;
                    }
                }
                $table3->addRow();
                $table3->addCell(2000, $cellColSpan312)->addText('');
                $table3->addCell(2000, $cellColSpan312)->addText('');
                $table3->addCell(2000, $cellColSpan312)->addText('');
                $table3->addCell(2000, $cellColSpan312)->addText('');
                $table3->addRow();
                $table3->addCell(2000, $cellColSpan312)->addText('');
                $table3->addCell(2000, $cellColSpan312)->addText('PRECIOS MAS 16 % DE I V A');
                $table3->addCell(2000, $cellColSpan312)->addText('');
                $table3->addCell(2000, $cellColSpan312)->addText('$' . '' . number_format($total, 2, '.', ','));
                //table4
                $table4 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
                $cellRowSpan1212 = array('vMerge' => 'restart');
                $cellRowContinue312 = array('vMerge' => 'continue');
                $cellColSpan1212 = array('gridSpan' => 3);
                $cellColSpan3122 = array('gridSpan' => 4);
                $cellColSpan21212 = array('gridSpan' => 4, 'bgColor' => '#808080');
                $estiloCelda1212 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
                $table4->addRow();
                $table4->addCell(2000, $cellColSpan21212)->addText('OBSERVACIONES', null, array('align' => 'center', 'bold' => true));
                $table4->addRow();
                $table4->addCell(2000, $cellColSpan3122)->addText('', null, array('align' => 'center'));
                $table4->addRow();
                $table4->addCell(2000, $cellColSpan3122)->addText('');
                $table4->addRow();
                $table4->addCell(2000, $cellColSpan3122)->addText('LOS SERVICIOS NO ESPECIFICADOS EN ESTA COTIZACIÓN SE COBRARAN A PRECIOS DE LISTA', null, array('align' => 'center', 'bold' => true));
                //tabla5
                $table5 = new Table(array('borderSize' => 12, 'borderColor' => '#080808', 'width' => 10100, 'unit' => TblWidth::TWIP));
                $cellRowSpan1212 = array('vMerge' => 'restart');
                $cellRowContinue312 = array('vMerge' => 'continue');
                $cellColSpan1212 = array('gridSpan' => 3);
                $cellColSpan3122 = array('gridSpan' => 4);
                $cellColSpan21212 = array('gridSpan' => 4, 'bgColor' => '#808080');
                $estiloCelda1212 = array('bgColor' => '#DCDCDC', 'bold' => true, 'size' => '20');
                $table5->addRow();
                $table5->addCell(2000, $cellColSpan21212)->addText('POR LA COMPAÑIA', null, array('align' => 'center', 'bold' => true));
                $table5->addCell(2000, $cellColSpan21212)->addText('POR EL CLIENTE', null, array('align' => 'center', 'bold' => true));
                $table5->addRow();
                $table5->addCell(2000, $cellColSpan3122)->addText('SILVESTRE OCTAVIANO GARCIA CARRILLO');
                $table5->addCell(2000, $cellColSpan3122)->addText($contratosCotizacion->cliente->user->name . ' ' . $contratosCotizacion->cliente->user->paterno . ' ' . $contratosCotizacion->cliente->user->materno);
                $table5->addRow();
                $table5->addCell(2000, $cellColSpan3122)->addText('SERVICIOS INTEGRADOS PRO-BLM DE MEXICO, S.A. DE C.V.');
                $table5->addCell(2000, $cellColSpan3122)->addText($contratosCotizacion->cliente->razon_social);
                $template->setComplexBlock('table', $table);
                $template->setComplexBlock('table2', $table2);
                $template->setComplexBlock('table4', $table4);
                $template->setComplexBlock('table3', $table3);
                $template->setComplexBlock('table5', $table5);
                $template->setValue('razonsocial', $contratosCotizacion->cliente->razon_social);
                $template->setValue('nombre', $contratosCotizacion->cliente->user->name . ' ' . $contratosCotizacion->cliente->user->paterno . ' ' . $contratosCotizacion->cliente->user->materno);
                $template->setValue('apoderada', $contratosCotizacion->apoderado);
                $template->setValue('escritura', $contratosCotizacion->escritura);
                $template->setValue('notario', $contratosCotizacion->notario);
                $template->setValue('licenciado', $contratosCotizacion->licenciado);
                $template->setValue('folio_mercantil', $contratosCotizacion->foliomercantil);
                $template->setValue('fecha_reg', $contratosCotizacion->fecharegistro);
                $template->setValue('lugar_registro', $contratosCotizacion->lugarregistro);
                $template->setValue('calle', $contratosCotizacion->cliente->direccion);
                $template->setValue('noInterior', $contratosCotizacion->cliente->direccion);
                $template->setValue('noExterior', $contratosCotizacion->cliente->direccion);
                $template->setValue('colonia', $contratosCotizacion->cliente->cp->colonia);
                $template->setValue('municipio', $contratosCotizacion->cliente->cp->municipio->municipio);
                $template->setValue('cp', $contratosCotizacion->cliente->cp->cp);
                $template->setValue('rfccliente', $contratosCotizacion->cliente->rfc);
                $template->setValue('fecha_reg_formt',$contratosCotizacion->fecharegistro);
                $tenpFile = tempnam(sys_get_temp_dir(), 'PHPWord');
                $template->saveAs($tenpFile);
    
                $header = [
                    "Content-Type: application/octet-stream",
                ];
    
                return response()->download($tenpFile, $this->rfc . '_' . $contratosCotizacion->ctg_contratos->path, $header)->deleteFileAfterSend($shouldDelete = true);
            } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
                //throw $th;
                return back($e->getCode());
            }
        }

}
