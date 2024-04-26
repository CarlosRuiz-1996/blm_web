<?php

namespace App\Livewire\Boveda;

use App\Livewire\Operaciones\RutaGestion;
use App\Models\Ruta;
use App\Models\RutaServicio;
use App\Models\Servicios;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $serviciosRuta;
    public function render()
    {
        $servicios = Ruta::where('ctg_rutas_estado_id', 2)->paginate(10);
        return view('livewire.boveda.index',compact('servicios'));
    }

    public function llenarmodalservicios($idruta){
        $this->serviciosRuta=RutaServicio::where('ruta_id', $idruta)->get();
    }
    public function cargar($idservicio)
    {
        $servicioRuta = RutaServicio::where('servicio_id', $idservicio)->first();

        if ($servicioRuta) {
            $servicioRuta->update(['status_ruta_servicios' => 2]);
            $this->llenarmodalservicios($servicioRuta->ruta_id); // Actualiza los datos
        }
    }

    public function cancelarCargar($idservicio)
    {
        $servicioRuta = RutaServicio::where('servicio_id', $idservicio)->first();

        if ($servicioRuta) {
            $servicioRuta->update(['status_ruta_servicios' => 1]);
            $this->llenarmodalservicios($servicioRuta->ruta_id); // Actualiza los datos
        }
    }
}


