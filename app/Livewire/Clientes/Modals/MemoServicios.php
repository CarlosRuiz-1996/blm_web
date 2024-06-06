<?php

namespace App\Livewire\Clientes\Modals;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
class MemoServicios extends Component
{

    public MemorandumForm $form;
    public function render()
    {
        return view('livewire.clientes.modals.memo-servicios', [
            'ctg_tipo_solicitud' => $this->form->getAllTipoSolicitud(),
            'ctg_tipo_servicio' => $this->form->getAllTipoServicio(),
            'ctg_horario_entrega' => $this->form->getAllHorarioEntega(),
            'ctg_dia_entrega' => $this->form->getAllDiaEntega(),
            'ctg_horario_servicio' => $this->form->getAllHorarioServicio(),
            'ctg_dia_servicio' => $this->form->getAllDiaServicio(),
            'ctg_consignatario' => $this->form->getAllConsignatorio(),


        ]);


        
    }

    protected $listeners = ['open-memorandum-clienteActivo' => 'handleOpen2'];
    public function handleOpen2()
    {
        dd('entra a memo');
        $this->dispatch('open-memo');
    }

    public function terminar (){
        Session::push('servicio-memo', [
            'horarioEntrega' => $this->form->horarioEntrega,
            'diaEntrega' => $this->form->diaEntrega,
            'horarioServicio' => $this->horarioServicio,
            'diaServicio' => $this->form->diaServicio,
            'consignatorio' => $this->form->consignatorio,
        ]);
        $this->dispatch('cliente-servicio-fin');
    }
}
