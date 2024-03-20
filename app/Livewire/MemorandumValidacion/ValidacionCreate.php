<?php

namespace App\Livewire\MemorandumValidacion;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;
use App\Livewire\Forms\MemoValidacionForm;
use App\Models\Memorandum;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class ValidacionCreate extends Component
{

    public $memorandum;
    public MemorandumForm $form;
    public MemoValidacionForm $form_validacion;

    public $memo_servicio;
    public $sucursales;
    public $area;
    public function mount(Memorandum $memorandum, $area)
    {
        $this->memo_servicio = $memorandum->memo_servicio;

        $this->memorandum = $memorandum;
        $this->area = $area;

        //seteo los datos de mi vista.
        $this->sucursales = $this->form->setMemoDetalles($memorandum);

        $this->memo_servicio = $memorandum->memo_servicio;
    }
    public function render()
    {
        return view('livewire.memorandum-validacion.validacion-create');
    }




    #[On('save-validacion')]
    public function save()
    {
        $res =  $this->form_validacion->store($this->area, $this->memorandum->id);
        if ($res == 1) {
            $this->dispatch('success', ["Memorandum completado con exito.",$this->area]);

        } else {
            $this->dispatch('error', ["No tienes permisos para validar el memorandum."]);
        }
    }
}
