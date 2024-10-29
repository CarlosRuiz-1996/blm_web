<?php

namespace App\Livewire\Reportegeneral;

use Livewire\WithPagination;
use App\Models\Cliente;
use App\Models\Servicios;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
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
    public $isOpenMovimientos=false; // Cantidad predeterminada de resultados por página

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

    public function render()
    {
        $clientes = Cliente::orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);
        //dd($clientes);
        return view('livewire.reportegeneral.reporte-general', compact('clientes'));
    }
    

    public function loadCliente($id)
    {
        $this->clienteSeleccionado = Cliente::find($id);
        $this->isOpen=true;
    }
    public function cerrarModal(){
        $this->clienteSeleccionado = null;
        $this->isOpen=false;
    }


    public function loadClienteMovimientos($id){
       $this->clienteMovimientos;
       $this->isOpenMovimientos=true;
    }
    
    public function loadClienteServicios($id){
        $this->clienteServicios=Servicios::where('cliente_id',$id)->get();
        $this->isOpenServicios=true;
        
    }
    public function cerrarModalServicios(){
        $this->clienteServicios = null;
        $this->isOpen=false;
    }
    public function cerrarModalMovimientos(){
        $this->clienteMovimientos = null;
        $this->isOpen=false;
    }
}

