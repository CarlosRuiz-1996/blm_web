<?php

namespace App\Livewire\Boveda;

use App\Exports\ClientesTotalServiciosExport;
use App\Models\Cliente;
use App\Models\Inconsistencias;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class TotalesServicios extends Component
{
    public $readyToLoad = false;
    public $fechaInicio;
    public $fechaFin;
    public $razonsocial;
    

    public function mount()
    {
        // Asignar el primer día del mes actual
    $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');

    // Asignar el último día del mes actual
    $this->fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
    public function render()
    {
        if ($this->readyToLoad) {
            // Cargar solo clientes que tengan servicios con rutas en el rango de fechas
            $clientes = Cliente::whereHas('servicios.ruta_servicios', function ($query) {
                // Filtrar las rutas por el rango de fechas
                $query->whereBetween('ruta_servicios.created_at', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            })
            ->with(['servicios.ruta_servicios' => function ($query) {
                // Cargar rutas que cumplen con el rango de fechas para la vista
                $query->whereBetween('ruta_servicios.created_at', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            }])
            // Filtrar por razón social si se proporciona
            ->when(!empty($this->razonsocial), function ($query) {
                $query->where('razon_social', 'like', '%' . $this->razonsocial . '%');
            })
            ->get();
        } else {
            $clientes = [];
        }
    
        return view('livewire.boveda.totales-servicios', compact('clientes'));
    } 
    public function exportarExcel()
    {
        return Excel::download(new ClientesTotalServiciosExport($this->fechaInicio, $this->fechaFin, $this->razonsocial), 'servicios_totales.xlsx');
    }
    



    public function loadTotalesSServicios()
    {
        $this->readyToLoad = true;
    }
}
