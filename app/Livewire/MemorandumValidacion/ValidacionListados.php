<?php

namespace App\Livewire\MemorandumValidacion;

use Livewire\Component;
use App\Livewire\Forms\MemoValidacionForm;
class ValidacionListados extends Component
{
    public MemoValidacionForm $form;
    public $area;
    public $name;
    public function mount($area, $name = null){
        $this->area = $area;
        $this->name = $name;

    }
    public function render()
    {
        $solicitudes = $this->form->getPendientes($this->area);

        $terminadas = $this->form->getValidados($this->area);

        return view('livewire.memorandum-validacion.validacion-listados', compact('solicitudes','terminadas'));
    }
}
