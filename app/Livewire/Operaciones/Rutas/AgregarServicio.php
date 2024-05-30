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
    public $selectServiciosEntrega = [];

    public $montoArray = [];
    public $folioArray = [];
    public $envaseArray = [];
    public $montoArrayRecolecta = [];
    public $folioArrayRecolecta = [];
    public $envaseArrayRecolecta = [];
    public $readyToLoad = false;
    public $selectValidacion = [];
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
        $this->montoArrayRecolecta = [];
        $this->folioArrayRecolecta = [];
        $this->envaseArrayRecolecta = [];
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
                    if (array_key_exists($id, $this->selectServiciosRecolecta)) {
                        if ($this->selectServiciosRecolecta[$id] === true) {
                            $rules["folioArrayRecolecta.$id"] = 'required';
                        }
                    }
                    if (array_key_exists($id, $this->selectServiciosEntrega)) {
                        if ($this->selectServiciosEntrega[$id] === true) {
                            $rules["montoArray.$id"] = 'required';
                            $rules["envaseArray.$id"] = 'required';
                            $rules["folioArray.$id"] = 'required';
                        }
                    }


                    if ((!array_key_exists($id, $this->selectServiciosRecolecta) || $this->selectServiciosRecolecta[$id] !== true) &&
                        (!array_key_exists($id, $this->selectServiciosEntrega) || $this->selectServiciosEntrega[$id] !== true)
                    ) {
                        $rules["selectValidacion.$id"] = 'required';
                    }
                }
            }
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'montoArray.*.required' => 'Campo obligatorio',
            'folioArray.*.required' => 'Campo obligatorio',
            'envaseArray.*.required' => 'Campo obligatorio',
            'folioArrayRecolecta.*.required' => 'Campo obligatorio',
            'selectValidacion.*.required' => 'Debes seleccionar almenos un tipo de entrega',

        ];
    }

    public function resetError($servicioId)
    {
        $this->resetErrorBag("montoArray.$servicioId");
        $this->resetErrorBag("folioArray.$servicioId");
        $this->resetErrorBag("envaseArray.$servicioId");
        $this->resetErrorBag("montoArrayRecolecta.$servicioId");
        $this->resetErrorBag("folioArrayRecolecta.$servicioId");
        $this->resetErrorBag("envaseArrayRecolecta.$servicioId");
        $this->resetErrorBag("selectValidacion.$servicioId");
    }


    #[On('add-servicio-ruta')]
    public function addServicios()
    {

        $this->selectServicios = array_filter($this->selectServicios);
        if (empty($this->selectServicios)) {

            $this->dispatch('error-servicio', ['Falta seleccionar servicios']);
        } else {

            $this->validate();
            $this->resetValidation();

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
                //entregas
                $seleccionadosRecolecta[$bandera] = [
                    "servicio_id" => $servicio_id,
                    "monto" => "",
                    "folio" => "",
                    "envases" => "",
                ];
                if (array_key_exists($servicio_id, $this->selectServiciosEntrega)) {
                    if (array_key_exists($servicio_id, $this->montoArray)) {
                        $seleccionados[$bandera]['monto'] = $this->montoArray[$servicio_id];
                    } else {
                        $seleccionados[$bandera]['monto'] = 0;
                    }
                    if (array_key_exists($servicio_id, $this->folioArray)) {
                        $seleccionados[$bandera]['folio'] = $this->folioArray[$servicio_id];
                    }
                    if (array_key_exists($servicio_id, $this->envaseArray)) {
                        $seleccionados[$bandera]['envases'] = $this->envaseArray[$servicio_id];
                    } else {
                        $seleccionados[$bandera]['envases'] = 0;
                    }
                } else if (array_key_exists($servicio_id, $this->selectServiciosRecolecta)) {
                    if (array_key_exists($servicio_id, $this->montoArrayRecolecta)) {
                        $seleccionadosRecolecta[$bandera]['monto'] = $this->montoArrayRecolecta[$servicio_id];
                    } else {
                        $seleccionadosRecolecta[$bandera]['monto'] = 0;
                    }
                    if (array_key_exists($servicio_id, $this->folioArrayRecolecta)) {
                        $seleccionadosRecolecta[$bandera]['folio'] = $this->folioArrayRecolecta[$servicio_id];
                    }
                    if (array_key_exists($servicio_id, $this->envaseArrayRecolecta)) {
                        $seleccionadosRecolecta[$bandera]['envases'] = $this->envaseArrayRecolecta[$servicio_id];
                    } else {
                        $seleccionadosRecolecta[$bandera]['envases'] = 0;
                    }
                }
                $bandera++;
            }
            

            $res = $this->form->storeRutaServicio($seleccionados, $seleccionadosRecolecta);

            if ($res == 1) {
                $this->dispatch('clean-servicios');
                $seleccionados = [];
                $this->dispatch('total-ruta');
                $this->dispatch('success-servicio', 'Servicios agregados con exito a la ruta');
                $this->dispatch('render-modal-vehiculos');
            } else {
                $this->dispatch('error-servicio', ['Ha ocurrido un problema, intenta mas tarde']);
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
            $this->dispatch('error-servicio', ['Ha ocurrido un problema, intenta mas tarde']);
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
            $this->dispatch('error-servicio', ['Ha ocurrido un problema, intenta mas tarde']);
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
