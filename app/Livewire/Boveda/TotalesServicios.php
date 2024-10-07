<?php

namespace App\Livewire\Boveda;

use App\Models\Cliente;
use App\Models\Inconsistencias;
use Carbon\Carbon;
use Livewire\Component;

class TotalesServicios extends Component
{
    public $readyToLoad = false;
    public $fechaInicio;
    public $fechaFin;
    public $razonsocial;
    

    public function mount()
    {
        // Asignar el primer día del mes
        $this->fechaInicio = '2024-01-01';
        
        // Asignar el último día del mes
        $this->fechaFin = '2024-10-07';
    }

    public function render()
    {
        if ($this->readyToLoad) {
            // Usar $this->fechaInicio y $this->fechaFin dentro de la consulta
            $clientes = Cliente::whereHas('servicios.ruta_servicios', function ($query) {
                $query->whereBetween('ruta_servicios.created_at', [Carbon::parse($this->fechaInicio), Carbon::parse($this->fechaFin)]); 
                // Asegurarse de que el campo sea el de 'ruta_servicios'
            })->get();
        } else {
            $clientes = [];
        }
        

        return view('livewire.boveda.totales-servicios', compact('clientes'));
    }

    public function loadTotalesSServicios()
    {
        $this->readyToLoad = true;
    }
}
