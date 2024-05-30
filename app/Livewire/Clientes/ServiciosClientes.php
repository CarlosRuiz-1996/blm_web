<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use App\Livewire\Forms\ClienteActivoForm;
use App\Models\Cliente;
use App\Models\Servicios;

class ServiciosClientes extends Component
{
    public ClienteActivoForm $form;

    public function mount(Cliente $cliente){
        $this->form->cliente = $cliente;

    }
    public function render()
    {
        $servicios = $this->form->getServicios();
        return view('livewire.clientes.servicios-clientes', compact('servicios'));
    }

    public function updateServicio(Servicios $servicio,$accion){
        $this->form->updateServicio($servicio,$accion);
        $this->render();
    }
}
