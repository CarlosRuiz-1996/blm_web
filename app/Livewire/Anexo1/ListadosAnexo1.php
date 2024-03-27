<?php

namespace App\Livewire\Anexo1;

use App\Models\Cotizacion;
use Livewire\Component;

class ListadosAnexo1 extends Component
{
    public $idsoli;
    public $cotizacionsoli;
    public $fechaIniciosoli;
    public $fechaFinsoli;
    public $solicitudes=[];
    public $terminadas=[];
    public function mount()
    {
        $this->solicitudes = Cotizacion::where('status_cotizacion','=',2)->get();
        $this->terminadas = Cotizacion::where('status_cotizacion','=',3)->get();

    }
    public function render()
    {
        return view('livewire.anexo1.listados-anexo1');
    }

    public function buscar()
{
    // Inicialmente, mostramos todas las cotizaciones si los filtros no están aplicados
    if (!$this->idsoli && !$this->cotizacionsoli && !$this->fechaIniciosoli && !$this->fechaFinsoli) {
        $this->solicitudes = Cotizacion::where('status_cotizacion', 2)->get();
    } else {
        // Si hay algún filtro aplicado, aplicamos los filtros
        $query = Cotizacion::query();

        if ($this->idsoli) {
            $query->where('id', $this->idsoli);
        }       
        if ($this->cotizacionsoli) {
            $query->where('campo_de_cotizacion', $this->cotizacionsoli);
        }
        if ($this->fechaIniciosoli) {
            $query->whereDate('created_at', '>=', $this->fechaIniciosoli);
        }
        if ($this->fechaFinsoli) {
            $query->whereDate('created_at', '<=', $this->fechaFinsoli);
        }

        // Aplicar otros filtros si es necesario

        // Obtener los resultados
        $this->solicitudes = $query->where('status_cotizacion', 2)->get();
    }
}
}
