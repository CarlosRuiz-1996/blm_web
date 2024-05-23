<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Livewire\Component;

class ResguardoCliente extends Component
{
    public $cliente;
    
    public function render()
    {
        
        $cliente=Cliente::find($this->cliente);
        $clienteResguardo=$cliente->resguardo;
        return view('livewire.clientes.resguardo-cliente',compact('clienteResguardo'));
    }
}
