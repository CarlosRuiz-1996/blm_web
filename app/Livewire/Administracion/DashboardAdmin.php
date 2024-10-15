<?php

namespace App\Livewire\Administracion;

use App\Models\Cliente;
use App\Models\CtgRutaDias;
use App\Models\Inconsistencias;
use App\Models\Reprogramacion;
use App\Models\Ruta;
use App\Models\RutaServicio;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DashboardAdmin extends Component
{
    use WithPagination, WithoutUrlPagination; 
    public $startDate;
    public $endDate;
    public $serviciosEntrega = [];
    public $serviciosRecoleccion = [];
    public $totalMontosEntrega;
    public $totalMontosRecoleccion;
    public $entregaServicios2;
    public $recoleccionServicios2;
    public $totalactas;
    public $totalreprogramacion;
    public function mount()
    {
        // Inicializa las fechas al inicio y fin del mes actual
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
    public function render()
    {
        $this->updateData();
        $this->datosMontos();
        $resguardototal = Cliente::where('status_cliente', 1)->sum('resguardo');
        $recoleccionServicios=$this->datosrecoleccion();
        $entregaServicios=$this->datosentrega();
        $inconsistencias=$this->datosinconsistencias();
        $reprogramacion=$this->datosreprogramacion();

        $diasrutas = CtgRutaDias::withCount('rutasdia')->get();
        $totalderutas=Ruta::count();
        return view('livewire.administracion.dashboard-admin',compact('diasrutas','totalderutas','recoleccionServicios','entregaServicios','resguardototal','inconsistencias','reprogramacion'));
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
public function datosMontos()
{
    // Verificar si ambos filtros están presentes
    if ($this->startDate && $this->endDate) {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

        // Sumar montos para servicios de entrega
        $this->totalMontosEntrega = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
            ->where('tipo_servicio', '1')
            ->sum('monto');

        // Sumar montos para servicios de recolección
        $this->totalMontosRecoleccion = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
            ->where('tipo_servicio', '2')
            ->sum('monto');
    }
}

public function datosrecoleccion()
{
    if ($this->startDate && $this->endDate) {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

        return RutaServicio::whereBetween('created_at', [$startDate, $endDate])
            ->where('tipo_servicio', '2')
            ->paginate(5, pageName: 'invoices-pagedos');
    }

    return RutaServicio::where('tipo_servicio', '2')->paginate(5, pageName: 'invoices-pagedos'); // Devuelve paginación por defecto
}

public function datosentrega()
{
    if ($this->startDate && $this->endDate) {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

        return RutaServicio::whereBetween('created_at', [$startDate, $endDate])
            ->where('tipo_servicio', '1')
            ->paginate(5, pageName: 'invoices-page');
    }

    return RutaServicio::where('tipo_servicio', '1')->paginate(5, pageName: 'invoices-page'); // Devuelve paginación por defecto
}
public function datosinconsistencias()
{
    if ($this->startDate && $this->endDate) {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
        $this->totalactas=Inconsistencias::whereBetween('fecha_comprobante', [$startDate, $endDate])->count();
        return Inconsistencias::whereBetween('fecha_comprobante', [$startDate, $endDate])
            ->paginate(5, pageName: 'invoices-page3');
    }

    return Inconsistencias::paginate(5, pageName: 'invoices-page3'); // Devuelve paginación por defecto
}

public function datosreprogramacion(){

    if ($this->startDate && $this->endDate) {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
        $this->totalreprogramacion=Reprogramacion::whereBetween('created_at', [$startDate, $endDate])->count();
        return Reprogramacion::whereBetween('created_at', [$startDate, $endDate])
            ->paginate(5, pageName: 'invoices-page4');
    }

    return Reprogramacion::paginate(5, pageName: 'invoices-page4'); // Devuelve paginación por defecto
}


}
