<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\ClienteActivoForm;
use App\Models\Cliente;
use Livewire\Attributes\On;

class ClienteActivoFormulario extends Component
{
    //llamar a coontrolador 
    public ClienteActivoForm $form;


    //inicalizar el componente con el el cliente seleccionado
    public function mount(Cliente $cliente)
    {
        if ($cliente->id) {
            $this->form->setCliente($cliente);
        }
    }

    //renderiza coon el catalogo con el tipo de cliente que es
    public function render()
    {
        $ctg_tipo_cliente =  $this->form->ctg_tipo_cliente();
        return view('livewire.cliente-activo-formulario', compact('ctg_tipo_cliente'));
    }

    //valida el codigo postal obteninedo  municipio y estado 
    public function validarCp()
    {
       //valida antes de buscar la informacion
        $this->validate([
            'form.cp' => 'required|digits_between:1,5',
        ], [
            'form.cp.digits_between' => 'El código postal solo contiene 5 digitos.',
            'form.cp.required' => 'Código postal requerido.',

        ]);
        //entra a la la funcion donde lo obtiene
        $this->form->validarCp();
    }


   //guarda la informacion del cliete
    #[On('save-cliente')]
    public function save()
    {
        $this->form->store();
        $this->dispatch('alert', ["El cliente se creo exitosamente."]);
    }
    // guarda  edicion del cliente
    #[On('edit-cliente')]
    public function updatedCliente()
    {
        $this->form->updated();
        $this->dispatch('alert', ["La informacion de cliente se actualizo exitosamente.", $this->form->cliente]);
    }
}
