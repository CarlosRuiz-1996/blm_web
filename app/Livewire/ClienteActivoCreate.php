<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\ClienteActivoForm;
use Livewire\Attributes\On;

class ClienteActivoCreate extends Component
{

    public ClienteActivoForm $form;
    public function render()
    {
        $ctg_tipo_cliente =  $this->form->ctg_tipo_cliente();
        return view('livewire.cliente-activo-create', compact('ctg_tipo_cliente'));
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
        $this->dispatch('alert', "El cliente se creo satisfactoriamente.");
    }

}
