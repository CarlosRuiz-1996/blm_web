<?php

namespace App\Livewire\Juridico;

use App\Models\Cliente;
use App\Models\Contratos_cotizacion;
use App\Models\Ctg_Contratos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;

class Consultacontratos extends Component
{
    public $inputId;
    public $cliente_id;
    public $inputFechaInicio;
    public $inputFechaFin;
    public $contratos;
    public $tipocontrato;
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

    protected $listeners = ['resetPagination'];
    use WithPagination, WithoutUrlPagination;
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
        $this->contratos = Ctg_Contratos::all();
    }
    public function resetPagination()
    {
        // Reiniciar la paginación cuando sea necesario desde fuera del componente
        $this->resetPage();
    }
    public function buscar()
    {
        // Filtrar los resultados según los valores del formulario
        $query = Contratos_cotizacion::query();

        if ($this->inputId) {
            $query->where('id', $this->inputId);
        }

        if ($this->cliente_id) {
            $query->where('cliente_id', $this->cliente_id);
        }

        if ($this->inputFechaInicio) {
            $query->where('created_at', '>=', $this->inputFechaInicio);
        }

        if ($this->inputFechaFin) {
            $query->where('created_at', '<=', $this->inputFechaFin);
        }

        if ($this->tipocontrato) {
            $query->where('ctg_contratos_id', $this->tipocontrato);
        }

        // Ejecutar la consulta
        $valor=$query->paginate(2);

        return $valor;
        
    }

    public function render()
    {
        $contratoslist=$this->buscar();
        return view('livewire.juridico.consultacontratos',compact('contratoslist'));
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

    }
    public function CancelarContrato($id)
    {
        $contratoCotizacion = Contratos_cotizacion::find($id);
        $contratoCotizacion->status_contrato = 0;
        $contratoCotizacion->save();

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
    
                return response()->download($tenpFile, $contratosCotizacion->cliente->rfc_cliente . '_' . $contratosCotizacion->ctg_contratos->path, $header)->deleteFileAfterSend($shouldDelete = true);
            } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
                //throw $th;
                return back($e->getCode());
            }
        }
}
