<?php

namespace App\Livewire\Memorandum;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;
use App\Models\Factibilidad;

class MemorandumGestion extends Component
{
    public MemorandumForm $form;
    public $cotizacion;
    public $factibilidad;
    public $sucursales;
    public function mount(Factibilidad $factibilidad){
        $this->factibilidad = $factibilidad;
        $this->form->razon_social = $factibilidad->cliente->razon_social;
        $this->form->rfc_cliente = $factibilidad->cliente->rfc_cliente;
        $this->form->ejecutivo = 'test';//$factibilidad->cliente->razon_social;
        $this->form->fecha_solicitud = $factibilidad->updated_at;

        $this->sucursales = $this->form->getSucursales($factibilidad->anexo1_id);
    }
    public function render()
    {

        return view('livewire.memorandum.memorandum-gestion',[
            'ctg_tipo_solicitud'=>$this->form->getAllTipoSolicitud(),
            'ctg_tipo_servicio'=>$this->form->getAllTipoServicio(),
            'ctg_horario_entrega'=>$this->form->getAllHorarioEntega(),
            'ctg_dia_entrega'=>$this->form->getAllDiaEntega(),
            'ctg_horario_servicio'=>$this->form->getAllHorarioServicio(),
            'ctg_dia_servicio'=>$this->form->getAllDiaServicio(),
            'ctg_consignatario'=>$this->form->getAllConsignatorio(),
            
            
        ]);
    }
}
