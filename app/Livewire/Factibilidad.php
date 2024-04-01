<?php

namespace App\Livewire;
use Barryvdh\DomPDF\Facade\Pdf;

use Livewire\Component;
use App\Livewire\Forms\FactibilidadForm;
use App\Models\Anexo1;
use App\Models\Ctg_Horario_Servicio;
use App\Models\Sucursal;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class Factibilidad extends Component
{
    use WithFileUploads;

    public FactibilidadForm $form;
    public $anexo;
    public $sucursales;
    public $active_form = 'show active';
    public $active_img = '';

    public function mount(Anexo1 $anexo)
    {

        $this->sucursales = $this->form->getSucursales($anexo);

        // dd($this->sucursales );
        $this->form->cliente_id = $anexo->cliente_id;
    }

    public function render()
    {
        $horarios = Ctg_Horario_Servicio::all();
        return view('livewire.factibilidad', compact('horarios'));
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
       
        $res =  $this->form->store($this->anexo,$this->foto_fachada, $this->foto_accesos, $this->foto_seguridad);
        // $this->reset(['foto_fachada','foto_accesos','foto_seguridad']);
        if ($res == 1) {
            $this->limpiarDatos();
            $this->dispatch('success', "El Reporte se genero correctamente.");
        } else {
            $this->dispatch('error', "Ha surgido un error, intente más tarde.");
        }
    }

    //imagenes
    public $foto_fachada = '';
    public $foto_accesos = '';
    public $foto_seguridad = '';

    public function subirImagen()
    {
        //para que no pierda el active la pestaña de las imagenes
        $this->active_form = '';
        $this->active_img = 'show active';
    }
    public function CambiarTab()
    {

        if ($this->active_form == 'show active' || $this->active_form == 'active') {

            $this->active_img = 'show active';
            $this->active_form = '';
        } elseif ($this->active_img = 'show active' || $this->active_img == 'active') {
            $this->active_form = 'show active';
            $this->active_img = '';
        }
    }
    #[On('limpiar')]
    public function limpiarDatos()
    {
        $this->form->sucursal_id = '';
        $this->form->fecha_evaluacion = '';
        $this->form->fecha_inicio_servicio = '';
        $this->form->ejecutivo = '';
        $this->form->sucursal_name = '';
        $this->form->direccion = '';
        $this->form->correo = '';
        $this->form->phone = '';
        $this->form->contacto = '';
        $this->form->cargo = '';
        $this->form->servicios = '';
        $this->form->razon_social = '';
        $this->form->rfc = '';
        $this->active_form = 'show active';
        $this->active_img = '';
    }


    //vizualizar reporte

    public function showPDF(Sucursal $sucursal)
    {
        
        $direccion = $this->form->direccionSucursal($sucursal);
        $factibilidad = $sucursal->factibilidades[0];

        $evaluador = $sucursal->factibilidades[0]->factibilidad->user;
        $pdf = Pdf::loadView('seguridad.reporte-pdf', compact('sucursal','direccion','factibilidad','evaluador'));
        $pdfData = $pdf->output();
        $pdfBase64 = base64_encode($pdfData);

        $this->dispatch('pdfGenerated', $pdfBase64);
    }
}
