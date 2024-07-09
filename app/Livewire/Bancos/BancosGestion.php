<?php

namespace App\Livewire\Bancos;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\BancosForm;

class BancosGestion extends Component
{

    use WithPagination;
    public BancosForm $form;
    public $readyToLoad = false;


    public function render()
    {

        if ($this->readyToLoad) {
            $resguardototal = $this->form->getCountResguadoClientes();
            $clientes = $this->form->getAllClientes();
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


    public function showMonto(Cliente $cliente)
    {
        $this->form->cliente = $cliente;
        
    }

    public function limpiarDatos()
    {
        $this->reset('form.cliente');
    }

    public function updating($property, $value)
    {
      
        if ($property === 'form.nuevo_monto') {
            $this->form->ingresa_monto =  $value + $this->form->cliente->resguardo;
        }
    }
}
