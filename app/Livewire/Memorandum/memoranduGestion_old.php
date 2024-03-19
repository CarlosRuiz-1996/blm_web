<?php

namespace App\Livewire\Memorandum;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;
use App\Models\Factibilidad;
use Illuminate\Support\Facades\Session;

class MemorandumGestion extends Component
{
    public MemorandumForm $form;
    public $cotizacion;
    public $factibilidad;
    public $sucursales;
    
    public $serviciosIds = [];

    public function mount(Factibilidad $factibilidad)
    {
        $this->factibilidad = $factibilidad;
        $this->form->razon_social = $factibilidad->cliente->razon_social;
        $this->form->rfc_cliente = $factibilidad->cliente->rfc_cliente;
        $this->form->ejecutivo = 'test'; //$factibilidad->cliente->razon_social;
        $this->form->fecha_solicitud = $factibilidad->updated_at;

        $this->sucursales = $this->form->getSucursales($factibilidad->anexo1_id);

        // foreach ($this->sucursales as $sucursal) {
        //     foreach ($sucursal['servicios'] as $servicio) {
        //         Session::push('complementos', [
        //             'sucursal_id' => $sucursal['sucursal']->id,
        //             'servicio_id' => $servicio->id, // Agrega el ID del servicio aquí
        //             'horarioEntrega' => '',
        //             'diaEntrega' => '',
        //             'horarioServicio' => '',
        //             'diaServicio' => '',
        //             'consignatorio' => '',
        //         ]);
        //     }
        // }
        // session()->forget('servicio-sucursal');
        foreach ($this->sucursales as $sucursal) {
            foreach ($sucursal['servicios'] as $servicio) {
                $this->serviciosIds[] = $servicio['id'];

            }
        }
    }

    public function rules()
    {
        $rules = [];
        foreach ($this->serviciosIds as $index => $servicioId) {
            $rules["horarioEntrega.{$servicioId}"] = 'required';
        }
        return $rules;

    }
    public function render()
    {

        return view('livewire.memorandum.memorandum-gestion', [
            'ctg_tipo_solicitud' => $this->form->getAllTipoSolicitud(),
            'ctg_tipo_servicio' => $this->form->getAllTipoServicio(),
            'ctg_horario_entrega' => $this->form->getAllHorarioEntega(),
            'ctg_dia_entrega' => $this->form->getAllDiaEntega(),
            'ctg_horario_servicio' => $this->form->getAllHorarioServicio(),
            'ctg_dia_servicio' => $this->form->getAllDiaServicio(),
            'ctg_consignatario' => $this->form->getAllConsignatorio(),


        ]);
    }
    public $horarioEntrega = [];
    public $diaEntrega = [];
    public $horarioServicio = [];
    public $diaServicio = [];
    public $consignatorio = [];

    // public function updatedHorarioEntrega($value, $servicioId)
    // {
    //     $servicioSucursal = Session::get('complementos');
    //     foreach ($servicioSucursal as &$item) {
    //         if ($item['servicio_id'] == $servicioId) {
    //             // Actualiza el valor de 'horarioEntrega' en el array
    //             $item['horarioEntrega'] = $value;
    //             break; // Termina el bucle una vez que se ha encontrado la entrada correspondiente
    //         }
    //     }
    //     // // Guarda el array actualizado de nuevo en la sesión
    //     Session::put('complementos', $servicioSucursal);
    // }
    // public function updatedDiaEntrega($value, $servicioId)
    // {
    //     $servicioSucursal = Session::get('complementos');
    //     foreach ($servicioSucursal as &$item) {
    //         if ($item['servicio_id'] == $servicioId) {
    //             // Actualiza el valor de 'horarioEntrega' en el array
    //             $item['diaEntrega'] = $value;
    //             break; // Termina el bucle una vez que se ha encontrado la entrada correspondiente
    //         }
    //     }
    //     // // Guarda el array actualizado de nuevo en la sesión
    //     Session::put('complementos', $servicioSucursal);
    // }
    // public function updatedHorarioServicio($value, $servicioId)
    // {
    //     $servicioSucursal = Session::get('complementos');
    //     foreach ($servicioSucursal as &$item) {
    //         if ($item['servicio_id'] == $servicioId) {
    //             // Actualiza el valor de 'horarioEntrega' en el array
    //             $item['horarioServicio'] = $value;
    //             break; // Termina el bucle una vez que se ha encontrado la entrada correspondiente
    //         }
    //     }
    //     // // Guarda el array actualizado de nuevo en la sesión
    //     Session::put('complementos', $servicioSucursal);
    // }
    // public function updatedDiaServicio($value, $servicioId)
    // {
    //     $servicioSucursal = Session::get('complementos');
    //     foreach ($servicioSucursal as &$item) {
    //         if ($item['servicio_id'] == $servicioId) {
    //             // Actualiza el valor de 'horarioEntrega' en el array
    //             $item['diaServicio'] = $value;
    //             break; // Termina el bucle una vez que se ha encontrado la entrada correspondiente
    //         }
    //     }
    //     // // Guarda el array actualizado de nuevo en la sesión
    //     Session::put('complementos', $servicioSucursal);
    // }
    // public function updatedConsignatorio($value, $servicioId)
    // {
    //     $servicioSucursal = Session::get('complementos');
    //     foreach ($servicioSucursal as &$item) {
    //         if ($item['servicio_id'] == $servicioId) {
    //             // Actualiza el valor de 'horarioEntrega' en el array
    //             $item['consignatorio'] = $value;
    //             break; // Termina el bucle una vez que se ha encontrado la entrada correspondiente
    //         }
    //     }
    //     // // Guarda el array actualizado de nuevo en la sesión
    //     Session::put('complementos', $servicioSucursal);
    // }


    public function save()
    {

        $this->validate();
        // $this->form->store();
    }
}
