<?php

namespace App\Livewire;

use App\Models\cotizacion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class CotizacionesIndexTabla extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public $idcotizacion;
    public $fechainicio;
    public $fechafin;
    public $nombrecliente;
    
    protected $listeners = ['resetPagination'];

    public function render()
    {
        $data = $this->BuscarCotizacion();
        return view('livewire.cotizaciones-index-tabla', compact('data'));
    }

    public function BuscarCotizacion()
    {
        $query = DB::table('cotizacion AS ctz')
            ->join('clientes AS cl', 'ctz.cliente_id', '=', 'cl.id')
            ->select('ctz.id', DB::raw('DATE(ctz.created_at) as fecha'), 'ctz.status_cotizacion', 'ctz.total', 'cl.razon_social', 'ctz.cliente_id');
    
        if (!empty($this->idcotizacion)) {
            $query->where('ctz.id', '=', $this->idcotizacion);
        }
    
        if (!empty($this->fechainicio)) {
            // Asumiendo que $this->fechainicio es una cadena de fecha 'Y-m-d'
            $query->whereDate('ctz.created_at', '>=', $this->fechainicio);
        }
    
        if (!empty($this->fechafin)) {
            // Asumiendo que $this->fechafin es una cadena de fecha 'Y-m-d'
            $query->whereDate('ctz.created_at', '<=', $this->fechafin);
        }
    
        if (!empty($this->nombrecliente)) {
            $query->where('cl.razon_social', 'like', '%' . $this->nombrecliente . '%');
        }
        $query->orderBy('ctz.id', 'desc');
        return $query->paginate(5);
    }
    

    public function updated($field)
    {
        // Cada vez que los filtros de búsqueda cambien, reiniciar la página
        if (in_array($field, ['idcotizacion', 'fechainicio', 'fechafin', 'nombrecliente'])) {
            $this->resetPage();
        }
    }

    public function resetPagination()
    {
        // Reiniciar la paginación cuando sea necesario desde fuera del componente
        $this->resetPage();
    }
}
