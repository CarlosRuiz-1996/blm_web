<?php

namespace App\Livewire\Reportegeneral;

use Livewire\WithPagination;
use App\Models\Cliente;
use App\Models\Servicios;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReporteGeneral extends Component
{
    use WithPagination;
    public $isOpen=false;
    public $sortColumn = 'id'; // Columna predeterminada de ordenación
    public $sortDirection = 'asc'; // Dirección predeterminada de ordenación
    public $perPage = 10;
    public $clienteSeleccionado = null;
    public $clienteMovimientos;
    public $isOpenServicios=false;
    public $clienteServicios;
    public $isOpenMovimientos=false; 
    public $fechaInicio;
    public $fechaFin;
    public $razonsocial;// Cantidad predeterminada de resultados por página
   //slecciona el filtro de la columa por la cual se ordena
    public function sortBy($column)
    {
        // Cambia la dirección de ordenación si la columna es la misma, de lo contrario reiníciala a ascendente
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumn = $column;
        $this->resetPage(); // Reinicia a la primera página al cambiar el orden
    }

    public function updatingPerPage()
    {
        $this->resetPage(); // Reinicia a la primera página al cambiar la cantidad de resultados por página
    }
    public function updatingFechaInicio()
    {
        $this->resetPage(); // Reinicia a la primera página al cambiar la fehca de inicio 
    }
    public function updatingFechaFin()
    {
        $this->resetPage(); // Reinicia a la primera página al cambiar la fechs fin 
    }
    public function updatingRazonsocial()
    {
        $this->resetPage(); // Reinicia a la primera página al cambiar la razon social  
    }

    public function render()
    {
        $clientes = Cliente::whereHas('servicios.ruta_servicios', function ($query) {
            // Filtrar las rutas por el rango de fechas
            $query->whereBetween('fecha_servicio', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        })
        ->with(['servicios' => function ($query) {
            $query->whereHas('ruta_servicios', function ($query) {
                // Filtrar los servicios que tengan rutas en el rango de fechas
                $query->whereBetween('fecha_servicio', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            })
            ->with(['ruta_servicios' => function ($query) {
                // Cargar rutas que cumplen con el rango de fechas para la vista
                $query->whereBetween('fecha_servicio', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            }]);
        }])
        // Filtrar por razón social si se proporciona
        ->when(!empty($this->razonsocial), function ($query) {
            $query->where('razon_social', 'like', '%' . $this->razonsocial . '%');
        })
        // Aplicar orden y dirección
        ->orderBy($this->sortColumn, $this->sortDirection)
        // Paginación
        ->paginate($this->perPage);

        //dd($clientes);
        return view('livewire.reportegeneral.reporte-general', compact('clientes'));
    }
    
    //carga iinformacion del cliente en modal y lo abre
    public function loadCliente($id)
    {
        $this->clienteSeleccionado = Cliente::find($id);
        $this->isOpen=true;
    }
    //cierra modal
    public function cerrarModal(){
        $this->clienteSeleccionado = null;
        $this->isOpen=false;
    }


    public function loadClienteMovimientos($id){
       $this->clienteMovimientos;
       $this->isOpenMovimientos=true;
    }
    //carga los servicios del cliente
    public function loadClienteServicios($id){
        $this->clienteServicios = Servicios::where('cliente_id', $id)
    ->when($this->fechaInicio && $this->fechaFin, function ($query) {
        $query->whereHas('ruta_servicios', function ($query) {
            $query->whereBetween('fecha_servicio', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        });
    })
    ->with(['ruta_servicios' => function ($query) {
        // Aplicar filtro de fechas para las rutas cargadas en la relación
        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('fecha_servicio', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }
    }])
    ->get();

        $this->isOpenServicios=true;
        
    }
    //cierra modal de los servicios del cliente y limpia
    public function cerrarModalServicios(){
        $this->clienteServicios = null;
        $this->isOpen=false;
    }
    public function cerrarModalMovimientos(){
        $this->clienteMovimientos = null;
        $this->isOpen=false;
    }

    //inicializa el componente
    public function mount()
    {
        // Asignar el primer día del mes actual
    $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');

    // Asignar el último día del mes actual
    $this->fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
}

