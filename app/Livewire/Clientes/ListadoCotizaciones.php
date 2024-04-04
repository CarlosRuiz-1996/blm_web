<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use App\Livewire\Forms\ClienteActivoForm;
use Livewire\WithPagination;
class ListadoCotizaciones extends Component
{

    use WithPagination;
    public ClienteActivoForm $form;
    public $cliente;
    

    public function render()
    {
        $cotizaciones = $this->form->getCotizaciones($this->cliente);
        // dd($cotizaciones);
        return view('livewire.clientes.listado-cotizaciones',compact('cotizaciones'));
    }
}
