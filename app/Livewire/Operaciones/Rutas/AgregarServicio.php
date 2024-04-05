<?php

namespace App\Livewire\Operaciones\Rutas;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class AgregarServicio extends Component
{
    use WithPagination;
    public RutaForm $form;
    public $selectServicios = [];
    public $montoArray = [];
    public $folioArray = [];
    public $envaseArray = [];

    protected $rules = [];
    public $clientes;
    public function mount(Ruta $ruta)
    {
        // Inicializar las variables de estado
        $this->form->ruta = $ruta;
        $this->selectServicios = [];
        $this->montoArray = [];
        $this->folioArray = [];
        $this->envaseArray = [];
        $this->clientes = $this->form->getClientes();
    }

    #[On('render-servicios')]
    public function render()
    {
        $servicios = $this->form->getServicios();
        return view('livewire.operaciones.rutas.agregar-servicio', compact('servicios'));
    }

    public function updated($property)
    {
        // $property: The name of the current property that was updated

        if ($property === 'form.searchClienteModal') {
            $this->form->searchClienteSelect = "";
        }
        if ($property === 'form.searchClienteSelect') {
            $this->form->searchClienteModal = "";
        }
    }

    public function limpiarFiltro()
    {
        $this->form->searchClienteSelect = "";

        $this->form->searchClienteModal = "";
    }
    public function rules()
    {

        $rules = [];

        if (!empty($this->selectServicios)) {
            foreach ($this->selectServicios as $id => $selected) {


                if ($selected) {

                    $rules["montoArray.$id"] = 'required';
                    $rules["folioArray.$id"] = 'required';
                    $rules["envaseArray.$id"] = 'required';
                }
            }
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'montoArray.*.required' => 'El campo monto es obligatorio para el servicio seleccionado',
            'folioArray.*.required' => 'El campo papeleta es obligatorio para el servicio seleccionado',
            'envaseArray.*.required' => 'El campo envases es obligatorio para el servicio seleccionado',
        ];
    }

    public function resetError($servicioId)
    {
        $this->resetErrorBag("montoArray.$servicioId");
        $this->resetErrorBag("folioArray.$servicioId");
        $this->resetErrorBag("envaseArray.$servicioId");
    }


    #[On('add-servicio-ruta')]
    public function addServicios()
    {
        $this->selectServicios = array_filter($this->selectServicios);
        if (empty($this->selectServicios)) {
            $this->dispatch('error-servicio', 'Falta seleccionar servicios');
        } else {
            $this->validate();
            $bandera = 0;
            $seleccionados = [];
            foreach ($this->selectServicios as $servicio_id => $item) {

                //creo mi arreglo
                $seleccionados[$bandera] = [
                    "servicio_id" => $servicio_id,
                    "monto" => "",
                    "folio" => "",
                    "envases" => "",
                ];

                if (array_key_exists($servicio_id, $this->montoArray)) {
                    $seleccionados[$bandera]['monto'] = $this->montoArray[$servicio_id];
                }
                if (array_key_exists($servicio_id, $this->folioArray)) {
                    $seleccionados[$bandera]['folio'] = $this->folioArray[$servicio_id];
                }
                if (array_key_exists($servicio_id, $this->envaseArray)) {
                    $seleccionados[$bandera]['envases'] = $this->envaseArray[$servicio_id];
                }
                $bandera++;
            }
            // dd($seleccionados);
            $res = $this->form->storeRutaServicio($seleccionados);

            if ($res == 1) {
                $this->montoArray = [];
                $this->folioArray = [];
                $this->envaseArray = [];
                $seleccionados = [];
                $this->dispatch('success-servicio', 'Servicios agregados con exito a la ruta');
                $this->dispatch('render-servicios');
            } else {
                $this->dispatch('error-servicio', 'Ha ocurrido un problema, intenta mas tarde');
            }
        }
    }


    public function getServicios()
    {
    }
}
