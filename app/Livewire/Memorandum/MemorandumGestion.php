<?php

namespace App\Livewire\Memorandum;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;
use App\Models\Factibilidad;
use Livewire\Attributes\On;

class MemorandumGestion extends Component
{
    public MemorandumForm $form;
    public $cotizacion;
    public $sucursales;


    //recibe de forma inicial por el metodo mount un obejto de factivilidad.
    //este objeto se manda cuando se llama al componente livewire desde una vista
    public function mount(Factibilidad $factibilidad)
    {
        $this->form->factibilidad = $factibilidad;
        $this->form->razon_social = $factibilidad->cliente->razon_social;
        $this->form->rfc_cliente = $factibilidad->cliente->rfc_cliente;
        $this->form->ejecutivo = 'test';
        $this->form->fecha_solicitud = $factibilidad->updated_at;

        $this->sucursales = $this->form->getSucursales($factibilidad->anexo1_id);

        
    }

    
    public function render()
    {

        //renderiza directamente los objetos que se ocuparan en la vista, para  los dropdown
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
    

//se implementa el formulario de memorandum y se guardan los datos
    #[On('save-memo')]
    public function save()
    {
        
        $res = $this->form->store();
        if ($res == 1) {
            $this->dispatch('success', ["Memorandum completado con exito."]);
        } else {
            $this->dispatch('error', ["A ocurrido un error, intente más tarde."]);
        }
    }
}
