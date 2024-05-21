<?php

namespace App\Livewire\Operaciones\Rutas;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use App\Models\RutaServicio;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class AgregarServicio extends Component
{
    use WithPagination;
    public RutaForm $form;
    public $selectServicios = [];
    public $selectServiciosRecolecta = [];

    public $montoArray = [];
    public $folioArray = [];
    public $envaseArray = [];
    public $readyToLoad = false;

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

    #[On('render-modal-vehiculos')]
    public function render()
    {
        if ($this->readyToLoad) {
            $servicios = $this->form->getServicios();
            $ruta_servicios = $this->form->getRutaServicios();
        } else {
            $servicios = [];
            $ruta_servicios = [];
        }
        return view('livewire.operaciones.rutas.agregar-servicio', compact('servicios', 'ruta_servicios'));
    }
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }

    public function getServicios()
    {
        $this->dispatch('render-modal-vehiculos');
        $this->readyToLoad = true;
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
                    if (!array_key_exists($id, $this->selectServiciosRecolecta)) {
                        $rules["montoArray.$id"] = 'required';
                        $rules["folioArray.$id"] = 'required';
                        $rules["envaseArray.$id"] = 'required';
                    }
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
                    "tipo" => "",
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
                if (array_key_exists($servicio_id, $this->selectServiciosRecolecta)) {
                    $seleccionados[$bandera]['tipo'] = 2;
                } else {
                    $seleccionados[$bandera]['tipo'] = 1;
                }
                $bandera++;
            }
            // dd($seleccionados);
            $res = $this->form->storeRutaServicio($seleccionados);

            if ($res == 1) {
                $this->dispatch('clean-servicios');
                $seleccionados = [];
                $this->dispatch('total-ruta');
                $this->dispatch('success-servicio', 'Servicios agregados con exito a la ruta');
                $this->dispatch('render-modal-vehiculos');
            } else {
                $this->dispatch('error-servicio');
            }
        }
    }

    #[On('delete-servicio')]
    public function DeleteServicio(RutaServicio $servicio)
    {
        $res = $this->form->deleteServicio($servicio);
        if ($res == 1) {
            $this->dispatch('success-servicio', 'Servicio eliminado con exito');
        } else {
            $this->dispatch('error-servicio');
        }
    }

    public function servicioEdit(RutaServicio $ruta_servicio)
    {

        $this->form->servicio_edit = $ruta_servicio;
        $this->form->monto = $ruta_servicio->monto;
        $this->form->folio = $ruta_servicio->folio;
        $this->form->envases = $ruta_servicio->envases;
        $this->form->servicio_desc = $ruta_servicio->servicio->cliente->razon_social;
    }

    #[On('update-servicio')]
    public function servicioUpdate()
    {
        $this->validate([
            'form.monto' => 'required',
            'form.folio' => 'required',
            'form.envases' => 'required',
        ], [
            'form.monto' => 'El monto es obligatorio.',
            'form.folio' => 'El folio es obligatorio.',
            'form.envases' => 'El envases es obligatorio',
        ]);
        $res = $this->form->updateServicio();
        if ($res == 1) {
            $this->dispatch('clean-servicios');
            $this->dispatch('total-ruta');
            $this->dispatch('success-servicio', 'Servicio actualizado con exito');
        } else {
            $this->dispatch('error-servicio');
        }
    }

    #[On('clean-servicios')]
    public function clean()
    {
        $this->reset(
            'form.monto',
            'form.folio',
            'form.envases',
            'form.servicio_desc',
            'form.servicio_edit',
            'selectServicios',
            'montoArray',
            'folioArray',
            'envaseArray',
            'selectServiciosRecolecta'
        );

        $this->resetValidation();
    }
}
