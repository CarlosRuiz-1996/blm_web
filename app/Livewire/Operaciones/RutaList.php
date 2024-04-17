<?php

namespace App\Livewire\Operaciones;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Cliente;
use App\Models\Ruta;
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
        $servicio_new = $this->form->getNewServicio();
        $dias = $this->form->getAllDias();
        $news = $this->form->countServiciosNews();
        return view('livewire.operaciones.ruta-list', compact('rutas', 'clientes', 'servicio_new', 'news', 'dias'));
    }



    // todos los servicios
    public $servicios = [];
    public function DetalleServicioCliente(Cliente $cliente)
    {
        $this->reset('servicios');
        $this->servicios = $this->form->DetalleServicioCliente($cliente);
    }


    #[On('clean-servicios')]
    public function clean()
    {
        $this->reset('servicios','rutas_dia','servicio','form.ctg_ruta_dia_id');
    }


    public $servicio;
    public function AgregarRuta(Servicios $servicio)
    {
        $this->servicio = $servicio;
        $this->dispatch('agregar-ruta');
    }

    public $rutas_dia;
    public function updating($property, $value)
    {
        if ($property === 'form.ctg_ruta_dia_id') {

            if($value != ""){
                $this->resetValidation('form.ctg_ruta_dia_id');
                $this->rutas_dia = Ruta::where('ctg_ruta_dia_id', '=', $value)->get();
            }else{
                $this->addError('form.ctg_ruta_dia_id', 'La fecha de evaluación debe ser menor a la fecha de inicio de servicio.');
            }
        }
    }
}
