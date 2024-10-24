<?php

namespace App\Livewire\MemorandumValidacion;

use Livewire\Component;
use App\Livewire\Forms\MemoValidacionForm;
use Livewire\WithPagination;
class ValidacionListados extends Component
{
    use WithPagination;
    public MemoValidacionForm $form;
    public $area;
    public $name;
    public $admin;

    public $readyToLoad=false;
    public function loadData()
    {
        $this->readyToLoad = true;
    }
    public function mount($area = null, $name = null, $admin = null)
    {
        $this->area = $area;
        $this->name = $name;
        $this->admin = $admin;
    }
    public function render()
    {

        if($this->readyToLoad)
        if ($this->admin==1) {
            $solicitudes = $this->form->getPendientesAdmin();
            $terminadas = $this->form->getValidadosAdmin();
        } else {
            $solicitudes = $this->form->getPendientes($this->area);
            $terminadas = $this->form->getValidados($this->area);
        }
        else{
            $solicitudes=[];
            $terminadas=[];
        }
        // dd($solicitudes);
        return view('livewire.memorandum-validacion.validacion-listados', compact('solicitudes', 'terminadas'));
    }
}
