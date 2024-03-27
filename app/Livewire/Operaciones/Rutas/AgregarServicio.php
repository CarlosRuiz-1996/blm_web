<?php

namespace App\Livewire\Operaciones\Rutas;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;

class AgregarServicio extends Component
{

    public RutaForm $form;
    public $selectServicios = [];
    public $montoArray = [];
    public $folioArray = [];
    public $envaseArray = [];
    public function render()
    {
        $this->selectServicios = [];
        $servicios = $this->form->getServicios();


        return view(
            'livewire.operaciones.rutas.agregar-servicio',
            [
                'servicios' => $servicios
            ]
        );
    }

    public function rules()
    {

        $rules = [];

        if (!empty($this->selectServicios)) {
            foreach ($this->selectServicios as $id => $selected) {


                if ($selected) {

                    $rules["montoArray.$id"] = 'required';
                }
            }
        }

        // dd($rules);
        return $rules;
    }

    public function addServicios()
    {
        // dd($this->selectServicios);
        $this->validate();
        // $rules = [];
        // $servicios = $this->selectServicios;
        // if (!empty($this->selectServicios)) {

        //     foreach ($this->selectServicios as $id => $selected) {


        //         if ($selected) {

        //             $rules["montoArray.$id"] = 'required';
        //         }
        //     }
        //     $this->validate($rules);
        //     $this->dispatch('success-servicio', ['Servicio agregados con exito']);
        //     $this->selectServicios = $servicios;
        // } else {
        //     $this->dispatch('error-servicio', 'No hay ningun servicio seleccionado');
        // }
    }

    public function getServicios()
    {
    }
}
