<?php

namespace App\Livewire\Clientes\Modals;

use App\Livewire\Forms\AnexoForm;
use App\Models\Cliente;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\On;

class AnexoServicios extends Component
{
    public AnexoForm $form;

    public function render()
    {
        return view('livewire.clientes.modals.anexo-servicios');
    }

    public function mount(Cliente $cliente)
    {
        $this->form->cliente_id = $cliente->id;
        $this->sucursales =  $this->form->getAllSucursal();
    }

    //sucursales para el modal
    public $sucursales;
    // public function getSucursales()
    // {
    //     dd($this->sucursales);
    // }
    protected $listeners = ['open-sucursal-servico-clienteActivo' => 'handleOpen'];

    public function handleOpen()
    {
        $this->dispatch('open-sucursal');
    }

    // Session::push('servicioForaneo', [
    public function sucursal_servicio()
    {


        $sucursal =  $this->form->getSucursalName();

        // dd($sucursal);
        Session::push('servicio-sucursal', [
            'sucursal_id' => $this->form->sucursal_id,
            'servicio_id' => $this->form->servicio_id,
            'nombre' => $sucursal->sucursal
        ]);
        $this->dispatch('sucursal-servico-memorandum');

    }


    #[On('save-sucursal-servicio')]
    public function save_sucursal()
    {
        $res = $this->form->store_sucursal();

        if ($res == 1) {
            $this->dispatch('success', ["La sucursal creo exitosamente.", 1]);
        } else {
            $this->dispatch('error', ["Ha ocurrido un error, intente más tarde.",1]);
        }
        $this->reset('sucursales');
    }
}
