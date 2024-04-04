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
    public $servicios;

    protected $rules = [];
    public function mount()
{
    // Inicializar las variables de estado
    $this->selectServicios = [];
    $this->montoArray = [];
    $this->folioArray = [];
    $this->envaseArray = [];
    $this->selectServicios = [];
    $this->servicios = $this->form->getServicios();
}
    public function render()
    {
        return view('livewire.operaciones.rutas.agregar-servicio');
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
        $rules = [];
        $this->selectServicios = array_filter($this->selectServicios);
        // Verificar si hay elementos seleccionados en selectServicios
        if (empty($this->selectServicios)) {
            // No se seleccionaron servicios, lanzar un dispatch con el mensaje correspondiente
            $rules = [];
            $this->resetValidation();
            $this->dispatch('error-servicio', 'Falta seleccionar servicios');
            return;
        }else{
    
        // Inicializar un array para acumular las reglas de validación
        $rules = [];
    
        // Iterar sobre los elementos seleccionados para validar los inputs
        foreach ($this->selectServicios as $id => $selected) {
            // Verificar si el servicio está seleccionado
            if ($selected) {
                // Agregar reglas de validación para este servicio
                $rules["montoArray.$id"] = 'required';
                $rules["folioArray.$id"] = 'required';
                $rules["envaseArray.$id"] = 'required';
            } else {
                // Si el servicio no está seleccionado, remover las reglas de validación asociadas a este servicio
                unset($rules["montoArray.$id"]);
                unset($rules["folioArray.$id"]);
                unset($rules["envaseArray.$id"]);
            }
        }
        
        // Aplicar las reglas de validación acumuladas
        if (!empty($rules)) {
        $this->validate($rules, [
            'montoArray.*.required' => 'El campo monto es obligatorio para el servicio',
            'folioArray.*.required' => 'El campo papeleta es obligatorio para el servicio',
            'envaseArray.*.required' => 'El campo envases es obligatorio para el servicio',
        ]);
    }
    }

    

    // Si se completaron todas las validaciones, aquí puedes agregar la lógica para guardar los datos u otras operaciones necesarias
}


    public function getServicios()
    {
    }

    

}
