<?php

namespace App\Livewire\Bancos;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\BancosForm;
use App\Models\BancosServicioAcreditacion;
use App\Models\BancosServicios;
use App\Models\CompraEfectivo;
use App\Models\CtgConsignatario;
use App\Models\DetallesCompraEfectivo;
use App\Models\MontoBlm;
use App\Models\Servicios;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class BancosGestion extends Component
{

    use WithPagination;
    public BancosForm $form;
    public $readyToLoad = false;
    public $readyToLoadModal = false;

    public $activeNav = ['active', '', '', ''];
    public $showNav = ['show', '', '', ''];


    protected $queryString = [
        'form.searchCliente' => ['except' => ''],
    ];

    public function ActiveNav($op)
    {
        foreach ($this->showNav as $key => $value) {
            $this->showNav[$key] = '';
        }
        foreach ($this->activeNav as $key => $value) {
            $this->activeNav[$key] = '';
        }

        $this->activeNav[$op] = 'active';

        $this->showNav[$op] = 'show';
    }
    public function render()
    {

        if ($this->readyToLoad) {
            $resguardototal = $this->form->getCountResguadoClientes();
            $resguardototalCliente = Cliente::where('status_cliente', 1)->sum('resguardo');

            $clientes = $this->form->getAllClientes();
            $servicios = $this->form->getAllBancosServicios();
            $compras = $this->form->getAllComprasEfectivo();
            // dd($clientes);
            $acreditaciones = BancosServicioAcreditacion::orderBy('id', 'DESC')->paginate(5, pageName: 'acreditaciones');
        } else {
            $resguardototal = 0;
            $resguardototalCliente = 0;
            $clientes = [];
            $servicios = [];
            $compras = [];
            $acreditaciones = [];
        }
        $clientes_activo = $this->form->getAllClientesActivo();
        $consignatarios = $this->form->getAllConsignatorio();
        return view('livewire.bancos.bancos-gestion', compact('acreditaciones', 'resguardototal', 'resguardototalCliente', 'clientes', 'servicios', 'compras', 'clientes_activo', 'consignatarios'));
    }
    public function loadClientes()
    {
        $this->readyToLoad = true;
    }
    public function updatingFormSearchCliente()
    {
        $this->resetPage();
    }

    public function showMonto(Cliente $cliente)
    {
        $this->form->cliente = $cliente;
        $this->form->actual_monto = $cliente->resguardo;
    }
    public $cliente_detail;

    public function showDetail(Cliente $cliente)
    {
        $this->cliente_detail = $cliente;
    }

    public function getMontosProperty()
    {
        return $this->cliente_detail->montos()->orderBy('id', 'DESC')->paginate(10, pageName: 'montos');
    }

    #[On('clean')]
    public function limpiarDatos()
    {
        $this->reset(
            'form.cliente',
            'readyToLoadModal',
            'form.actual_monto',
            'form.nuevo_monto',
            'cliente_detail',
            'acreditacion_detail',
            'ticket'
        );
    }

    public function updating($property, $value)
    {

        if ($property === 'form.ingresa_monto') {
            if ($value != "") {
                $this->form->nuevo_monto =  $value + $this->form->cliente->resguardo;
            } else {
                $this->form->nuevo_monto = 0;
            }
        }
    }

    public function add()
    {
        $res =  $this->form->addMonto();

        if ($res == 1) {
            $this->dispatch('alert', ['Se modifico el monto del cliente.', 'success']);
            $this->render();
            $this->limpiarDatos();
        } else {

            $this->dispatch('alert', [$res == 0 ? 'Hubo un problema, intenta más tarde.' : $res, 'error']);
        }
    }

    //detalle compra
    public $compra_detalle = [];
    public function showCompraDetail(CompraEfectivo $compra)
    {
        $this->compra_detalle = $compra;
        $this->readyToLoadModal = true;
    }


    // servicios bancos

    public $cliente;
    public $servicio;
    public $monto_e = 0;
    public $cajero_id;
    public $fecha;
    public $total;
    public $tipo;
    public $papeleta;

    public $compras_efectivo = [];
    public function addCompra()
    {
        $this->validate(
            [
                'cajero_id' => 'required',
                'monto_e' => 'required',
            ],
            [
                'cajero_id.required' => 'El cajero es obligatorio',
                'monto_e.required' => 'El monto es obligatorio',
            ]
        );
        $cajero = CtgConsignatario::find($this->cajero_id);
        $this->compras_efectivo[] = [
            "cajero" => $this->cajero_id,
            "cajero_name" => $cajero->name,
            "monto" => $this->monto_e,
        ];
        $this->total += $this->monto_e;
        $this->reset(['cajero_id', 'monto_e']);
        // $this->dispatch('resetSelect2');
    }
    public function removeCompra($index)
    {
        unset($this->compras_efectivo[$index]);
        $this->compras_efectivo = array_values($this->compras_efectivo); // Reindexar el array
        if (!count($this->compras_efectivo)) {
            $this->reset(['cajero_id', 'fecha']);
        }
    }

    public $servicios_cliente = [];
    //seleccionar servicios del cliente:

    public function updatedCliente($value)
    {
        if ($value != '')
            $this->servicios_cliente = Servicios::where('cliente_id', $value)->where('status_servicio', '>=', 3)->get();
    }

    public function finalizarCompra()
    {

        $this->validate(
            [
                // 'cajero_id' => 'required',
                'fecha' => 'required',
            ],
            [
                // 'cajero_id.required' => 'El cajero es obligatorio',
                'fecha.required' => 'La fecha es obligatorio',
            ]
        );

        try {
            DB::beginTransaction();

            if (!count($this->compras_efectivo)) {
                throw new \Exception('No hay servicios para guardar');
            }
            //guardar compra efectivo
            $compra_efectivo = CompraEfectivo::create([
                'total' => $this->total,
                'fecha_compra' => $this->fecha,
            ]);
            foreach ($this->compras_efectivo as  $compra) {
                DetallesCompraEfectivo::create([
                    'compra_efectivo_id' => $compra_efectivo->id,
                    'monto' => $compra['monto'],
                    'consignatario_id' => $compra['cajero'],
                ]);
            }
            DB::commit();
            $this->clean();
            $this->dispatch('alert', ['La compra de efectivo se mando a operaciones', 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            $this->dispatch('alert', [$e->getMessage(), 'error']);
        }
    }

    public function clean()
    {
        $this->reset([
            'cliente',
            'monto_e',
            'cajero_id',
            'fecha',
            'servicios_add',
            'servicio',
            'total',
            'compras_efectivo',
            'servicios_cliente',
            'tipo',
            'papeleta',
        ]);
    }

    public $direccion;
    public $servicios_add = [];
    public function addServicios()
    {


        $this->validate(
            [
                'cliente' => 'required',
                'servicio' => 'required',
                'papeleta' => 'required',
                'fecha' => 'required',
                'tipo' => 'required',
                'monto_e' => 'required_if:tipo,1', // Aquí se usa la regla condicional

            ],
            [
                'cliente.required' => 'El cliente es obligatorio',
                'monto_e.required_if' => 'El monto es obligatorio',
                'servicio.required' => 'El servicio es obligatorio',
                'papeleta.required' => 'La papeleta es obligatorio',
                'fecha.required' => 'La fecha es obligatorio',
                'tipo.required' => 'El tipo es obligatorio',
            ]
        );
        $cliente = Cliente::find($this->cliente);
        $servicio = Servicios::find($this->servicio);
        $this->servicios_add[] = [
            "cliente" => $this->cliente,
            "cliente_name" => $cliente->razon_social . '-' . $cliente->rfc_cliente,
            "monto" => $this->monto_e,
            "tipo_id" => $this->tipo,
            "tipo_servicio" => $this->tipo == 1 ? 'Entrega' : 'Recolecta',
            "fecha" => $this->fecha,
            "papeleta" => $this->papeleta,
            "servicio" => $this->servicio,
            "servicio_desc" => $servicio->ctg_servicio->descripcion,
            "direccion" => $this->direccion

        ];
        $this->reset(['cliente', 'monto_e', 'papeleta', 'servicio', 'tipo', 'fecha', 'direccion']);
        $this->dispatch('resetSelect2');
    }
    public function updatedServicio($value)
    {
        if ($value != '')
            $direccion = Servicios::find($value);
        $this->direccion = $direccion->sucursal ? $direccion->sucursal->sucursal->sucursal . ', ' . $direccion->sucursal->sucursal->direccion .
            ', ' .  $direccion->sucursal->sucursal->cp->cp .
            '' .
            $direccion->sucursal->sucursal->cp->estado->name : 'No tiene una sucursal definida aun.';
    }
    public function removeService($index)
    {
        unset($this->servicios_add[$index]);
        $this->servicios_add = array_values($this->servicios_add); // Reindexar el array

    }


    public function finalizarServicios()
    {
        try {
            DB::beginTransaction();

            if (!count($this->servicios_add)) {
                throw new \Exception('No hay servicios para guardar');
            }
            //guardar servicios desde bancos
            foreach ($this->servicios_add as  $servicio) {
                BancosServicios::create([
                    'servicio_id' => $servicio['servicio'],
                    'monto' => $servicio['monto'],
                    'papeleta' => $servicio['papeleta'],
                    'fecha_entrega' => $servicio['fecha'],
                    'tipo_servicio' => $servicio['tipo_id'],
                ]);
            }
            DB::commit();
            $this->clean();
            $this->dispatch('alert', ['Los servicios mandaron a operaciones', 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', [$e->getMessage(), 'error']);
        }
    }


    // acreditaciones
    public $acreditacion_detail;
    public $ticket;
    public function addTickect(BancosServicioAcreditacion $acreditacion)
    {
        $this->acreditacion_detail = $acreditacion;
    }

    public function finalizarAcreditacion(){
        $this->validate(['ticket'=>'required'],['ticket.required'=>'El ticket es requerido']);

        try{
            DB::beginTransaction();
            
            $this->acreditacion_detail->folio = $this->ticket;
            $this->acreditacion_detail->status_acreditacion = 2;
            $this->acreditacion_detail->save();

            MontoBlm::find(1)->increment('monto', $this->acreditacion_detail->envase->cantidad);

            $this->dispatch('alert', ['El folio se guardo correctamente y el monto se sumo al monto total de blm.', 'success']);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            $this->dispatch('alert', ['Hubo un problema, intenta más tarde.', 'error']);

        }
    }
}
