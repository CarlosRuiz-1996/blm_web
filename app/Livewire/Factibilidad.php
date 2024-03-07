<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\FactibilidadForm;
use App\Models\Sucursal;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

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
        $this->form->cliente_id = $this->cliente->id;
        $this->sucursales =  $this->form->getSucursales();
        return view('livewire.factibilidad');
    }


    public function DetalleSucursal(Sucursal $sucursal)
    {
        $this->form->DetalleSucursal($sucursal);
    }

    public function DetalleReporte(Sucursal $sucursal)
    {
        $this->form->DetalleReporte($sucursal);
        
    }
    public function save_form()
    {
        $this->form->store($this->foto_fachada,$this->foto_accesos,$this->foto_seguridad);
        // $this->reset(['foto_fachada','foto_accesos','foto_seguridad']);
        $this->limpiarDatos();
        $this->dispatch('success', "El Reporte se genero correctamente.");

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

    #[On('limpiar')]
    public function limpiarDatos(){
        $this->form->sucursal_id = '';
        $this->form->fecha_evaluacion = '';
        $this->form->fecha_inicio_servicio = '';
        $this->form->ejecutivo = '';
        $this->form->sucursal_name= '';
        $this->form->direccion = '';
        $this->form->correo = '';
        $this->form->phone = '';
        $this->form->contacto = ''; 
        $this->form->cargo = '';
        $this->form->servicios = '';
        $this->form->razon_social = '';
        $this->form->rfc = '';
    }
}
