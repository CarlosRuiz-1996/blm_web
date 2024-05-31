<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\ClienteActivoForm;
use App\Models\Cliente;
use Livewire\Attributes\On;

class ClienteActivoFormulario extends Component
{

    public ClienteActivoForm $form;

    public function mount(Cliente $cliente)
    {
        if ($cliente->id) {
            $this->form->setCliente($cliente);
        }
    }
    public function render()
    {
        $ctg_tipo_cliente =  $this->form->ctg_tipo_cliente();
        return view('livewire.cliente-activo-formulario', compact('ctg_tipo_cliente'));
    }


    public function validarCp()
    {

        $this->validate([
            'form.cp' => 'required|digits_between:1,5',
        ], [
            'form.cp.digits_between' => 'El código postal solo contiene 5 digitos.',
            'form.cp.required' => 'Código postal requerido.',

        ]);

        $this->form->validarCp();
    }



    #[On('save-cliente')]
    public function save()
    {
        $this->form->store();
        $this->dispatch('alert', ["El cliente se creo exitosamente."]);
    }

    #[On('edit-cliente')]
    public function updatedCliente()
    {
        $this->form->updated();
        $this->dispatch('alert', ["La informacion de cliente se actualizo exitosamente.", $this->form->cliente]);
    }
}
