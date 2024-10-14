<?php

namespace App\Livewire\Operaciones\Rutas;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use App\Models\RutaServicio;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\WithoutUrlPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Modelable;


class AgregarServicio extends Component
{
    use WithPagination,WithoutUrlPagination;
    public RutaForm $form;
    public $selectServicios = [];
    public $selectServiciosRecolecta = [];
    public $selectServiciosEntrega = [];

    public $montoArray = [];
    public $folioArray = [];
    public $montoArrayRecolecta = [];
    public $folioArrayRecolecta = [];
    public $readyToLoad = false;
    public $selectValidacion = [];
    protected $rules = [];
    public $clientes;
    #[Modelable]
    public $dia_id_calendario;
    public function mount(Ruta $ruta)
    {
        // Inicializar las variables de estado
        $this->form->ruta = $ruta;
        $this->selectServicios = [];
        $this->montoArray = [];
        $this->folioArray = [];
        $this->montoArrayRecolecta = [];
        $this->folioArrayRecolecta = [];
        $this->clientes = $this->form->getClientes();
        
    }

    #[On('render-modal-servicios')]
    public function render()
    {

        if ($this->readyToLoad) {
            $this->form->dia_id_calendario=$this->dia_id_calendario;
            $servicios = $this->form->getServicios();
            $ruta_servicios = $this->form->getRutaServicios();
            foreach ($servicios as $servicio) {
                if (!isset($this->selectServicios[$servicio->id])) {
                    // Limpiar valores de monto y folio si el servicio no está seleccionado
                    $this->montoArray[$servicio->id] = null;
                    $this->folioArray[$servicio->id] = null;
                    $this->montoArrayRecolecta[$servicio->id] = null;
                    $this->folioArrayRecolecta[$servicio->id] = null;
                }
            }
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
        $this->render();
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
    public function handleCheckboxChange($servicioId)
    {
        if ($this->selectServicios[$servicioId]) {
            // Checkbox está marcado, agregar lógica si es necesario
            $this->selectServicios[$servicioId] = true;
        } else {
            // Checkbox está desmarcado, limpiar los montos y folios
            $this->selectServicios[$servicioId] = false;
    
            // Deshabilitar y limpiar valores
            $this->montoArray[$servicioId] = null;
            $this->folioArray[$servicioId] = null;
            $this->montoArrayRecolecta[$servicioId] = null;
            $this->folioArrayRecolecta[$servicioId] = null;
        }
    }
    
    
        public function handleCheckboxChangeRecolecta($servicioId)
        {
            // Verificar si el checkbox de recolección fue desmarcado
            if (!$this->selectServiciosRecolecta[$servicioId]) {
                // El checkbox está desmarcado, deshabilitar y limpiar los campos
                $this->montoArrayRecolecta[$servicioId] = null;
                $this->folioArrayRecolecta[$servicioId] = null;
            }
        }
        
        public function handleCheckboxChangeEntrega($servicioId)
        {
            // Verificar si el checkbox de entrega fue desmarcado
            if (!$this->selectServiciosEntrega[$servicioId]) {
                // El checkbox está desmarcado, deshabilitar y limpiar los campos
                $this->montoArray[$servicioId] = null;
                $this->folioArray[$servicioId] = null;
                
            }
        }

        public function updatedMontoArray()
        {
            // Iterar sobre el array para aplicar lógica a cada elemento
            foreach ($this->montoArray as $servicioId => $value) {
                if ($value < 0) {
                    $this->montoArray[$servicioId] = 0; // o cualquier lógica que necesites
                } else {
                    $this->montoArray[$servicioId] = $value;
                }
            }
        }


        public function updatedFolioArray()
        {
            // Iterar sobre el array para aplicar lógica a cada elemento
            foreach ($this->folioArray as $servicioId => $value) {
                if (empty($value)) {
                    $this->folioArray[$servicioId] = ''; // Asegúrate de que el valor sea una cadena vacía si es necesario
                } else {
                    $this->folioArray[$servicioId] = $value;
                }
            }
        }
        public function updatedMontoArrayRecolecta()
        {
            // Iterar sobre el array de montos recolecta
            foreach ($this->montoArrayRecolecta as $servicioId => $value) {
                if ($value < 0) {
                    $this->montoArrayRecolecta[$servicioId] = 0; // Establecer a 0 si es negativo
                } else {
                    $this->montoArrayRecolecta[$servicioId] = $value; // Mantener el valor si es válido
                }
            }
        }
        public function updatedFolioArrayRecolecta()
        {
            // Iterar sobre el array de montos recolecta
            foreach ($this->folioArrayRecolecta as $servicioId => $value) {
                if ($value < 0) {
                    $this->folioArrayRecolecta[$servicioId] = 0; // Establecer a 0 si es negativo
                } else {
                    $this->folioArrayRecolecta[$servicioId] = $value; // Mantener el valor si es válido
                }
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
                    
                    if (array_key_exists($id, $this->selectServiciosEntrega)) {
                        if ($this->selectServiciosEntrega[$id] === true) {
                            $rules["montoArray.$id"] = 'required';
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
            'selectValidacion.*.required' => 'Debes seleccionar almenos un tipo de entrega',

        ];
    }

    public function resetError($servicioId)
    {
        $this->resetErrorBag("montoArray.$servicioId");
        $this->resetErrorBag("selectValidacion.$servicioId");
    }


    #[On('add-servicio-ruta')]
    public function addServicios()
    {

        $this->selectServicios = array_filter($this->selectServicios);
        if (empty($this->selectServicios)) {

            $this->dispatch('error-servicio', ['Falta seleccionar servicios']);
        } else {
           if($this->selectServiciosRecolecta && !$this->selectServiciosEntrega){

           }else{
            $this->validate();
           }
            
            $this->resetValidation();


            $bandera = 0;
            $seleccionados = [];
            $seleccionadosRecolecta = [];
            foreach ($this->selectServicios as $servicio_id => $item) {

                //entregas

                if (array_key_exists($servicio_id, $this->selectServiciosEntrega)) {
                    //creo mi arreglo
                    $seleccionados[$bandera] = [
                        "servicio_id" => $servicio_id,
                        "monto" => "",
                        "folio" => "",
                    ];

                    if (array_key_exists($servicio_id, $this->montoArray)) {
                        $seleccionados[$bandera]['monto'] = $this->montoArray[$servicio_id];
                    } else {
                        $seleccionados[$bandera]['monto'] = 0;
                    }
                    if (array_key_exists($servicio_id, $this->folioArray)) {
                        $seleccionados[$bandera]['folio'] = $this->folioArray[$servicio_id];
                    }
                    
                }
                if (array_key_exists($servicio_id, $this->selectServiciosRecolecta)) {
                    $seleccionadosRecolecta[$bandera] = [
                        "servicio_id" => $servicio_id,
                        "monto" => "",
                        "folio" => "",
                    ];
                    if (array_key_exists($servicio_id, $this->montoArrayRecolecta)) {
                        $seleccionadosRecolecta[$bandera]['monto'] = $this->montoArrayRecolecta[$servicio_id];
                    } else {
                        $seleccionadosRecolecta[$bandera]['monto'] = 0;
                    }
                    if (array_key_exists($servicio_id, $this->folioArrayRecolecta)) {
                        $seleccionadosRecolecta[$bandera]['folio'] = $this->folioArrayRecolecta[$servicio_id];
                    }
                    
                }
                $bandera++;
            }


            $res = $this->form->storeRutaServicio($seleccionados, $seleccionadosRecolecta);

            if ($res == 1) {
                $this->clean();
                $seleccionados = [];
                $seleccionadosRecolecta = [];
                $this->selectServiciosEntrega=[];
                $this->selectServicios=[];
               
                $this->dispatch('total-ruta');
                $this->dispatch('success-servicio', 'Servicios agregados con exito a la ruta');
                $this->render();
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
            $this->render();
        } else {
            $this->dispatch('error-servicio', ['Ha ocurrido un problema, intenta mas tarde']);
        }
    }

    public function servicioEdit(RutaServicio $ruta_servicio)
    {

        $this->form->servicio_edit = $ruta_servicio;
        $this->form->monto = $ruta_servicio->monto;
        $this->form->folio = $ruta_servicio->folio;
        $this->form->servicio_desc = $ruta_servicio->servicio->cliente->razon_social;
    }

    #[On('update-servicio')]
    public function servicioUpdate()
    {
        $this->validate([
            'form.monto' => 'required',
            'form.folio' => 'required',
        ], [
            'form.monto' => 'El monto es obligatorio.',
            'form.folio' => 'El folio es obligatorio.',
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
            'form.servicio_desc',
            'form.servicio_edit',
            'selectServicios',
            'montoArray',
            'folioArray',
            'selectServiciosRecolecta',
            'montoArrayRecolecta',
            'folioArrayRecolecta',
          
        );

        $this->resetValidation();
    }
}