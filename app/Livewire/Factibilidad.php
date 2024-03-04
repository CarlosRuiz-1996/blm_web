<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\FactibilidadForm;
use App\Models\Sucursal;
use Livewire\WithFileUploads;

class Factibilidad extends Component
{
    use WithFileUploads;

    public FactibilidadForm $form;
    public $cliente;
    public $sucursales;
    public $active_form = 'show active';
    public $active_img = '';
    public function render()
    {
        $this->sucursales =  $this->form->getSucursales();
        return view('livewire.factibilidad');
    }


    public function DetalleSucursal(Sucursal $sucursal)
    {
        $this->form->DetalleSucursal($sucursal);
    }
    public function save_form()
    {
        $this->form->store($this->foto_fachada,$this->foto_accesos,$this->foto_seguridad);
        // $this->reset(['foto_fachada','foto_accesos','foto_seguridad']);
    }

    //imagenes
    public $foto_fachada='';
    public $foto_accesos='';
    public $foto_seguridad='';

    public function subirImagen()
    {
        //para que no pierda el active la pestaña de las imagenes
        $this->active_form = '';
        $this->active_img = 'show active';
    }
}
