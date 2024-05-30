<?php

namespace App\Livewire\MemorandumValidacion;

use Livewire\Component;
use App\Livewire\Forms\MemoValidacionForm;

class ValidacionListados extends Component
{
    public MemoValidacionForm $form;
    public $area;
    public $name;
    public $admin;
    public function mount($area = null, $name = null, $admin = null)
    {
        $this->area = $area;
        $this->name = $name;
        $this->admin = $admin;
    }
    public function render()
    {
        if ($this->admin==1) {
            $solicitudes = $this->form->getPendientesAdmin();
            $terminadas = $this->form->getValidadosAdmin();
        } else {
            $solicitudes = $this->form->getPendientes($this->area);
            $terminadas = $this->form->getValidados($this->area);
        }
        return view('livewire.memorandum-validacion.validacion-listados', compact('solicitudes', 'terminadas'));
    }
}
