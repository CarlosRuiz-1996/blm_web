<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\AnexoForm;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Session;
class Anexo1 extends Component
{

    public AnexoForm $form;
 
    public $cliente;


    
    public function render()
    {
        $servicios = $this->form->getAllServicios($this->cliente->id);
        return view('livewire.anexo1');
    }


    public function validarCp()
    {

        // dd('entra');
        $this->validate([
            'form.cp' => 'required|digits_between:1,5',
        ], [
            'form.cp.digits_between' => 'El código postal solo contiene 5 digitos.',
            'form.cp.required' => 'Código postal requerido.',

        ]);

        $this->form->validarCp();
    }

    #[On('save-sucursal')]
    public function save_sucursal()
    {
        $this->form->cliente_id = $this->cliente->id;
        $this->form->store_sucursal();
        $this->dispatch('alert', ["La sucursal creo exitosamente.",1]);
    }


    public $sucursales;
    public function getSucursales(){
        $this->sucursales =  $this->form->getAllSucursal();
    }

    //relacionar sucursal y servicios
    public function sucursal_servicio(){

        Session::push('servicio-sucursal', [
            'sucursal_id' => $this->form->sucursal_id,
            'servicio_id' => $this->form->servicio_id,
        ]);
    }
    public function servicio($servicio_id){

        $this->form->servicio_id = $servicio_id;
       
    }


    #[On('save-servicios')]
    public function save()
    {
        return redirect()->route('roles.index')->with('success', 'Permiso eliminad con exito!');
        // $this->dispatch('error', "No hay servicios con sucursales.");
        // if (Session::has('servicio-sucursal')) {
        //     // dd('entra');
        //     // $this->form->store();
        //     session()->forget('servicio-sucursal');
        //     $this->dispatch('success', "Anexo completado con exito.");
            
        // } else {
        //     // dd('error');
        //     $this->dispatch('error', "  No hay servicios con sucursales.");
        // }
    }
}
