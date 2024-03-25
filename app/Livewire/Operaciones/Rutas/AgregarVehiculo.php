<?php

namespace App\Livewire\Operaciones\Rutas;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\CtgVehiculos;
use App\Models\Ruta;
use App\Models\RutaVehiculo;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class AgregarVehiculo extends Component
{
    use WithPagination;

    public RutaForm $form;
    public $selectVehiculos = [];
    public $entrada = array('5', '10', '15', '20', '50', '100');
    public $list = '5';
    public $readyToLoad = false;
    public $sort = "updated_at";
    public $orderBy = "desc";

    public function mount(Ruta $ruta)
    {
        $this->form->ruta = $ruta;
    }

    protected $queryString = [
        'list' => ['except' => '5'],
        'sort' => ['except' => 'updated_at'],
        'orderBy' => ['except' => 'desc'],
        'form.searchVehiculo' => ['except' => ''],
        'form.searchVehiculoModal' => ['except' => ''],

    ];


    #[On('render-modal-vehiculos')]
    public function render()
    {
        if ($this->readyToLoad) {
            $vehiculos = $this->form->getVehiculos($this->sort, $this->orderBy, $this->list);
            $ruta_vehiculos = $this->form->getRutaVehiculos();
        } else {
            $vehiculos = [];
            $ruta_vehiculos = [];
        }
        return view(
            'livewire.operaciones.rutas.agregar-vehiculo',
            [
                'vehiculos' => $vehiculos,
                'ruta_vehiculos' => $ruta_vehiculos
            ]
        );
    }
    public function loadVehiculos()
    {
        $this->readyToLoad = true;
    }

    public function getVehiculos()
    {
        $this->dispatch('render-modal-vehiculos');
        $this->readyToLoad = true;
    }

    #[On('add-vehiculos-ruta')]
    public function AddVehiculos()
    {


        if (!empty($this->selectVehiculos)) {

            foreach ($this->selectVehiculos as $vehiculo_id => $i) {
                if ($i) {
                    $this->form->storeVehiculos($vehiculo_id);
                }
            }

            $this->dispatch('success-vehiculo', ['Vehículos agregados con exito']);
            $this->selectVehiculos = [];
        } else {
            $this->dispatch('error-vehiculo', 'No hay ningun vehículo seleccionado');
        }
    }
    #[On('clean-vehiculos')]
    public function clean()
    {
        $this->selectVehiculos = [];
    }
    #[On('delete-vehiculo')]
    public function DeleteVehiculo(RutaVehiculo $vehiculo)
    {
        $this->form->deleteVehiculos($vehiculo);
        $this->dispatch('success-vehiculo', ['Vehículo eliminado con exito']);
    }

    public function addServicio()
    {
        // foreach ($this->selectVehiculos as $vehiculo_id => $i) {
        //     $vehiculo = CtgVehiculos::find($vehiculo_id);
        //     Session::push('vehiculos-agregados', [
        //         'vehiculo' => $vehiculo,
        //     ]);
        // }
        // $this->vehiculos_session = Session::get('vehiculos-agregados', []);

        // Session::forget('vehiculos-agregados' . $index);
        // $this->vehiculos_session = Session::get('vehiculos-agregados', []);
    }
}
