<?php

namespace App\Livewire\Memorandum;

use App\Models\Memorandum;
use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;
use Livewire\Attributes\On;

class MemorandumValidando extends Component
{
    public $memorandum;
    public MemorandumForm $form;
    public $memo_servicio;
    public $sucursales;

    public function mount(Memorandum $memorandum)
    {
        $this->memo_servicio = $memorandum->memo_servicio;

        $this->memorandum = $memorandum;
        //seteo los datos de mi vista.
        $this->sucursales = $this->form->setMemoDetalles($memorandum);

        $this->memo_servicio = $memorandum->memo_servicio;

       
    }
    public function render()
    {
        $firmas = $this->form->getFirmas($this->memorandum->id);

        return view('livewire.memorandum.memorandum-validando',compact('firmas'));
    }


    #[On('save-finalizacion')]
    public function save()
    {
        $res =  $this->form->storeFinalizar($this->memorandum);
        if ($res == 1) {
            $this->dispatch('success', ["Memorandum Finalizado con exito."]);

        } else {
            $this->dispatch('error', ["Ha ocurrido un error, intente más tarde."]);
        }
    }
}
