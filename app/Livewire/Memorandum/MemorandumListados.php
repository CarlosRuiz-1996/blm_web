<?php

namespace App\Livewire\Memorandum;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;

class MemorandumListados extends Component
{
    public MemorandumForm $form;

    public function render()
    {
        $solicitudes = $this->form->getFactibilidadAll();
        $proceso = $this->form->getMemorandumValidacion();
        $terminadas = $this->form->getMemorandumTerminado();

        return view('livewire.memorandum.memorandum-listados',compact('solicitudes','proceso','terminadas'));
    }
}
