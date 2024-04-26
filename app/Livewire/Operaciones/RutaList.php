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


    #[On('render-rutas')]
    public function render()
    {
        $clientes = $this->form->getAllServicios();
        $servicio_new = $this->form->getNewServicio();
        $rutasdiasiguiente= $this->form->getIdDiaSiguiente();
        $dias = $this->form->getAllDias();
        $news = $this->form->countServiciosNews();
        return view('livewire.operaciones.ruta-list', compact('rutas', 'clientes', 'servicio_new', 'news', 'dias','rutasdiasiguiente'));
    }
    public function filtrarRutas()
{
    $this->dispatch('render-rutas');
}
    



    // todos los servicios
    public $servicios = [];
    public function DetalleServicioCliente(Cliente $cliente)
    {
        $this->reset('servicios');
        $this->servicios = $this->form->DetalleServicioCliente($cliente);
        // dd($this->servicios);
    }


    #[On('clean-servicios')]
    public function clean()
    {
        $this->reset(
            'servicios',
            'rutas_dia',
            'servicio',
            'form.ctg_ruta_dia_id',
            'form.ruta_id',
            'form.monto',
            'form.folio',
            'form.envases',
        );
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

            if ($value != "") {
                $this->resetValidation('form.ctg_ruta_dia_id');
                $this->rutas_dia = Ruta::where('ctg_ruta_dia_id', '=', $value)->get();

                $this->form->ruta_id = "";
                $this->form->monto = "";
                $this->form->folio = "";
                $this->form->envases = "";
            } else {

                $this->addError('form.ctg_ruta_dia_id', 'La fecha de evaluación debe ser menor a la fecha de inicio de servicio.');

                $this->form->ruta_id = "";
                $this->form->monto = "";
                $this->form->folio = "";
                $this->form->envases = "";
            }
        }
    }


    #[On('save-servicio-ruta')]
    public function saveServicioRuta()
    {
        $this->validate([
            'form.ruta_id' => 'required',
            'form.monto' => 'required',
            'form.folio' => 'required',
            'form.envases' => 'required',
        ], [
            'form.ruta_id' => 'La ruta es obligatorio.',
            'form.monto' => 'El monto es obligatorio.',
            'form.folio' => 'El folio es obligatorio.',
            'form.envases' => 'El envases es obligatorio',
        ]);

        $res = $this->form->storeServicioRuta($this->servicio->id);

        if ($res) {
            $this->clean();
            $this->dispatch('render-rutas');
            $this->dispatch('success'['El servicio se agrego a la ruta.']);
        } else {
            $this->dispatch('error');
        }
    }
}
