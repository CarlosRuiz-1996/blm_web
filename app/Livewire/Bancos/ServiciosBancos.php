<?php

namespace App\Livewire\Bancos;

use Livewire\Component;
use App\Livewire\Forms\BancosForm;
use App\Models\BancosServicios;
use App\Models\Cliente;
use App\Models\CompraEfectivo;
use App\Models\DetallesCompraEfectivo;
use App\Models\Servicios;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class ServiciosBancos extends Component
{
    public BancosForm $form;
    public $cliente;
    public $servicio;
    public $monto;
    public $cajero_id;
    public $fecha;
    public $total;
    public $tipo;
    public $papeleta;
    public function render()
    {
        $clientes = $this->form->getAllClientesActivo();
        $consignatarios = $this->form->getAllConsignatorio();
        return view('livewire.bancos.servicios-bancos', compact('clientes', 'consignatarios'));
    }

    public $compras = [];
    public function addCompra()
    {
        $this->validate(
            [
                'cliente' => 'required',
                'monto' => 'required',
            ],
            [
                'cliente.required' => 'El cliente es obligatorio',
                'monto.required' => 'El monto es obligatorio',
            ]
        );
        $cliente = Cliente::find($this->cliente);
        $this->compras[] = [
            "cliente" => $this->cliente,
            "cliente_name" => $cliente->razon_social . '-' . $cliente->rfc_cliente,
            "monto" => $this->monto,
        ];
        $this->total += $this->monto;
        $this->reset(['cliente', 'monto']);
        $this->dispatch('resetSelect2');

    }
    public function removeCompra($index)
    {
        unset($this->compras[$index]);
        $this->compras = array_values($this->compras); // Reindexar el array
        if (!count($this->compras)) {
            $this->reset(['cajero_id', 'fecha']);
        }
    }

    public $servicios_cliente = [];
    //seleccionar servicios del cliente:

    public function updatedCliente($value)
    {
        if($value!='')
        $this->servicios_cliente = Servicios::where('cliente_id', $value)->get();
    }

    public function finalizarCompra()
    {

        $this->validate(
            [
                'cajero_id' => 'required',
                'fecha' => 'required',
            ],
            [
                'cajero_id.required' => 'El cajero es obligatorio',
                'fecha.required' => 'La fecha es obligatorio',
            ]
        );

        try {
            DB::beginTransaction();

            if (!count($this->compras)) {
                throw new \Exception('No hay servicios para guardar');
            }
            //guardar compra efectivo
            $compra = CompraEfectivo::create([
                'consignatario_id' => $this->cajero_id,
                'total' => $this->total,
                'fecha_compra' => $this->fecha,
            ]);
            foreach ($this->compras as  $compra) {
                DetallesCompraEfectivo::create([
                    'compra_efectivo_id' => $compra->id,
                    'monto' => $compra['monto'],
                    'cliente_id' => $compra['cliente'],
                ]);
            }
            DB::commit();
            $this->clean();
            $this->dispatch('alert', ['La compra de efectivo se mando a operaciones', 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            // $this->dispatch('error', [$e->getMessage()]);
            // dd();
            $this->dispatch('alert', [$e->getMessage(), 'error']);
            Log::info('Info: ' . $e);
        }
    }

    public function clean()
    {
        $this->reset(['cliente', 'monto', 'cajero_id', 'fecha', 'servicio', 'total', 'compras', 'servicios', 'tipo', 'papeleta']);
    }

    public $servicios = [];
    public function addServicios()
    {


        $this->validate(
            [
                'cliente' => 'required',
                'servicio' => 'required',
                'papeleta' => 'required',
                'fecha' => 'required',
                'tipo' => 'required',
                // 'monto' => 'required',
                'monto' => 'required_if:tipo,1', // Aquí se usa la regla condicional

            ],
            [
                'cliente.required' => 'El cliente es obligatorio',
                'monto.required_if' => 'El monto es obligatorio',
                'servicio.required' => 'El servicio es obligatorio',
                'papeleta.required' => 'La papeleta es obligatorio',
                'fecha.required' => 'La fecha es obligatorio',
                'tipo.required' => 'El tipo es obligatorio',
            ]
        );
        $cliente = Cliente::find($this->cliente);
        $servicio = Servicios::find($this->servicio);
        $this->servicios[] = [
            "cliente" => $this->cliente,
            "cliente_name" => $cliente->razon_social . '-' . $cliente->rfc_cliente,
            "monto" => $this->monto,
            "tipo_id" => $this->tipo,
            "tipo_servicio" => $this->tipo == 1 ? 'Entrega' : 'Recolecta',
            "fecha" => $this->fecha,
            "papeleta" => $this->papeleta,
            "servicio" => $this->servicio,
            "servicio_desc" => $servicio->ctg_servicio->descripcion

        ];
        $this->reset(['cliente', 'monto', 'papeleta', 'servicio', 'tipo', 'fecha']);
        $this->dispatch('resetSelect2');
    }

    public function removeService($index)
    {
        unset($this->servicios[$index]);
        $this->servicios = array_values($this->servicios); // Reindexar el array

    }


    public function finalizarServicios()
    {

        try {
            DB::beginTransaction();

            if (!count($this->servicios)) {
                throw new \Exception('No hay servicios para guardar');
            }
            //guardar servicios desde bancos

            foreach ($this->servicios as  $servicio) {
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
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            // $this->dispatch('error', [$e->getMessage()]);
            // dd();
            $this->dispatch('alert', [$e->getMessage(), 'error']);
            // Log::info('Info: ' . $e);
        }
    }

    
}
