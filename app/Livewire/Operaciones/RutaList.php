<?php

namespace App\Livewire\Operaciones;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Cliente;
use App\Models\RutaServicio;
use App\Models\Servicios;
use Livewire\Attributes\On;
class RutaList extends Component
{
    public RutaForm $form;
    public function render()
    {
        $rutas = $this->form->getAllRutas();
        $clientes = $this->form->getAllServicios();
        return view('livewire.operaciones.ruta-list',compact('rutas','clientes'));
    }



    // todos los servicios
    public $servicios=[];
    public function DetalleServicioCliente(Cliente $cliente){

        $this->servicios = $this->form->DetalleServicioCliente($cliente);

        // dd($this->servicios);
    }


    #[On('clean-servicios')]
    public function clean(){
        $this->reset('servicios');
    }
}
