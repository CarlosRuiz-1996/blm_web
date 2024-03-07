<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;

class MemorandumCreate extends Component
{
    public MemorandumForm $form;
    public $cotizacion;
    public function render()
    {   
        
        return view('livewire.memorandum-create',[
            'ctg_tipo_solicitud'=>$this->form->getAllTipoSolicitud(),
            'ctg_tipo_servicio'=>$this->form->getAllTipoServicio()
        ]);
    }
}
