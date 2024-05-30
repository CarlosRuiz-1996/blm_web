<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use App\Livewire\Forms\ClienteActivoForm;
use App\Models\Cliente;
use App\Models\ctg_precio_servicio;
use App\Models\ctg_servicios;
use App\Models\CtgServicios;
use App\Models\Servicios;

class ServiciosClientes extends Component
{
    public ClienteActivoForm $form;
    public $foraneos = false;
    public $precio_servicio;
    public $editarPreciohabilitado = false;
    public $cantidadhabilitado = false;
    public $listaForaneos = [];
    public $editIndex = null; // Índice de la fila que se está editando
    public $bloqser;
    public $folioctg;
    public $tipoctg;
    public $descripcionctg;
    public $unidadctg;
    public $servicios;

    public function mount(Cliente $cliente){
        $this->form->cliente = $cliente;
        $this->precio_servicio = ctg_precio_servicio::all();
        $this->servicios = ctg_servicios::all();
    }
    public function render()
    {
        $servicios_cliente = $this->form->getServicios();

        return view('livewire.clientes.servicios-clientes', compact('servicios_cliente'));
    }

    public function updateServicio(Servicios $servicio,$accion){
        $this->form->updateServicio($servicio,$accion);
        $this->render();
    }


    public function crearServicioctg()
    {

        $sumar =2+1;

        $result = $sumar +45;
        // $this->validate([
        //     'folioctg' => 'required|unique:ctg_servicios,folio',
        //     'tipoctg' => 'required',
        //     'descripcionctg' => 'required',
        //     'unidadctg' => 'required',
        // ], [
        //     'folioctg.required' => 'El folio es requerido.',
        //     'folioctg.unique' => 'El folio ya existe en la tabla de servicios.',
        //     'tipoctg.required' => 'El tipo es requerido.',
        //     'descripcionctg.required' => 'La descripción es requerida.',
        //     'unidadctg.required' => 'La unidad es requerida.',
        // ]);
        // CtgServicios::create([
        //     'folio' => $this->folioctg,
        //     'tipo' => $this->tipoctg,
        //     'descripcion' => $this->descripcionctg,
        //     'unidad' => $this->unidadctg,
        //     'status_servicio' => 1,
        // ]);
        // $this->folioctg = '';
        // $this->tipoctg = '';
        // $this->descripcionctg = '';
        // $this->unidadctg = '';
        // $this->servicios = ctg_servicios::all();
        // Despachar el evento
        $this->dispatch('successservicio', ['El servicio se creó con éxito'.$result]);
    }
}
