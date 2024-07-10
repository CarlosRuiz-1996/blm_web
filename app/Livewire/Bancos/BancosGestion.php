<?php

namespace App\Livewire\Bancos;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\BancosForm;
use Livewire\Attributes\On;

class BancosGestion extends Component
{

    use WithPagination;
    public BancosForm $form;
    public $readyToLoad = false;
    public $readyToLoadModal = false;

    protected $queryString = [
        'form.searchCliente' => ['except' => ''],
    ];

    public function render()
    {

        if ($this->readyToLoad) {
            $resguardototal = $this->form->getCountResguadoClientes();
            $clientes = $this->form->getAllClientes();
            // dd($clientes);

        } else {
            $resguardototal = 0;
            $clientes = [];
        }
        return view('livewire.bancos.bancos-gestion', compact('resguardototal', 'clientes'));
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
    public $cliente_detail=[];
    
    public function showDetail(Cliente $cliente)
    {

        $this->cliente_detail = $cliente;
        $this->readyToLoadModal = true;
    }

    #[On('clean')]
    public function limpiarDatos()
    {
        $this->reset('form.cliente', 'readyToLoadModal','form.actual_monto', 'form.nuevo_monto', 'form.ingresa_monto', 'cliente_detail');
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
            $this->dispatch('alert', ['Hubo un problema, intenta más tarde.', 'error']);
        }
    }
}
