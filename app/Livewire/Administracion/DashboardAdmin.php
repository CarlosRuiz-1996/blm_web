<?php

namespace App\Livewire\Administracion;

use App\Models\RutaServicio;
use Carbon\Carbon;
use Livewire\Component;

class DashboardAdmin extends Component
{
    public $startDate;
    public $endDate;
    public $serviciosEntrega = [];
    public $serviciosRecoleccion = [];
    public function mount()
    {
        // Inicializa las fechas al inicio y fin del mes actual
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
    public function render()
    {
        $this->updateData();
        return view('livewire.administracion.dashboard-admin');
    }

    public function updateData()
{
    // Verificar si ambos filtros están presentes
    if ($this->startDate && $this->endDate) {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
        
        // Consultas para entrega y recolección con el rango de fechas
        $this->serviciosEntrega = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
            ->where('tipo_servicio', '1')
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->pluck('cantidad', 'fecha')
            ->toArray();

        $this->serviciosRecoleccion = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
            ->where('tipo_servicio', '2')
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->pluck('cantidad', 'fecha')
            ->toArray();
        
        // Emitir evento para actualizar gráficos
        $this->dispatch('updatedChartData', serviciosEntrega:$this->serviciosEntrega, serviciosRecoleccion:$this->serviciosRecoleccion);
    }
}

}
