<?php

namespace App\Livewire\Clientes\Modals;

use App\Livewire\Forms\AnexoForm;
use App\Models\Cliente;
use Livewire\Component;

class AnexoServicios extends Component
{
    public AnexoForm $form;

    public function render()
    {
        return view('livewire.clientes.modals.anexo-servicios');
    }

    public function mount(Cliente $cliente){
        $this->form->cliente_id = $cliente->id;
    }

    //sucursales para el modal
    public $sucursales;
    public function getSucursales()
    {
        $this->sucursales =  $this->form->getAllSucursal();
    }
    protected $listeners = ['open-sucursal-servico-clienteActivo' => 'handleOpen'];

    public function handleOpen()
    {
        $this->dispatch('open-sucursal');
    }

}
