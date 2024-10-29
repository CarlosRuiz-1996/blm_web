<?php

namespace App\Livewire\Catalogos;

use Livewire\Component;
use App\Livewire\Forms\Catalogos\VehiculosForm;
use App\Models\CtgVehiculos;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Vehiculos extends Component
{
    public VehiculosForm $form;
    public $readyToLoad = false;
    use WithPagination, WithoutUrlPagination; 
    public $modelos;
    public $search;
    public function loadTable()
    {
        $this->readyToLoad = true;
    }
  
    public function render()
    {
        if($this->readyToLoad){
            $vehiculos = $this->form->getAllVehiculos($this->search);
            $marcas = $this->form->getAllMarcas();
        }else{
            $vehiculos =[];
            $marcas = [];
        }
        return view('livewire.catalogos.vehiculos', compact('vehiculos', 'marcas'));
    }
   

    public function updated($property)
    {

        if ($property === 'form.ctg_vehiculo_marca_id') {
            $this->reset('form.ctg_vehiculo_modelo_id');
            if ($this->form->ctg_vehiculo_marca_id != 0) {
                $this->modelos = $this->form->getAllModelosByMarca();
            } 
        }
    }
    #[On('save-vehiculo')]
    public function save()
    {
        $res = $this->form->storeVehiculo();
        if ($res == 0) {
            $this->dispatch('datatable');
            $this->validate();

        }
        $this->dispatch('success', "El vehiculo se guardo con excito.");

    }

    #[On('update-vehiculo')]
    public function update()
    {

        $res = $this->form->updateVehiculo($this->vehiculo);
        if ($res == 0) {
            $this->dispatch('datatable');
            $this->validate();

        }
        $this->dispatch('success', "El vehiculo se actualizo con exito.");

    }

    #[On('baja-vehiculo')]
    public function baja(CtgVehiculos $vehiculo)
    {
        $this->form->bajaVehiculo($vehiculo);
        $this->dispatch('success', "El vehiculo se dio de baja correctamente.");
    }
    #[On('reactivar-vehiculo')]
    public function reactivar(CtgVehiculos $vehiculo)
    {
        $this->form->reactivarVehiculo($vehiculo,);
        $this->dispatch('success', "El vehiculo se reactivo correctamente.");
    }
    #[On('delete-vehiculo')]
    public function delete(CtgVehiculos $vehiculo)
    {
        $res =  $this->form->delete($vehiculo);
        if ($res == 1) {
            $this->dispatch('success', "El vehiculo se dio de baja correctamente.");
        } else {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }
    public function setVehiculo(CtgVehiculos $vehiculo){

        $this->limpiar();
        $this->vehiculo_id = $vehiculo->id;
        $this->vehiculo = $vehiculo;
        $this->form->descripcion = $vehiculo->descripcion;
        $this->form->placas = $vehiculo->placas;
        $this->form->anio = $vehiculo->anio;
        $this->form->serie = $vehiculo->serie;
        $this->form->ctg_vehiculo_modelo_id = $vehiculo->modelo->id;
        $this->form->ctg_vehiculo_marca_id = $vehiculo->modelo->marca->id;

        $this->dispatch('edit-vehiculo');
    }

    public $vehiculo;
    public $vehiculo_id;
    public function limpiar()
    {
        
        $this->vehiculo_id = 0;
        $this->vehiculo = '';
        $this->form->descripcion ="";
        $this->form->placas ="";
        $this->form->anio ="";
        $this->form->serie ="";
        $this->form->ctg_vehiculo_modelo_id ="";
        $this->form->ctg_vehiculo_marca_id ="";
        $this->dispatch('datatable');
    }
}
