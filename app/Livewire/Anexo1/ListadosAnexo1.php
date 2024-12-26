<?php

namespace App\Livewire\Anexo1;

use App\Models\Anexo1;
use App\Models\Cotizacion;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class ListadosAnexo1 extends Component
{
    use WithPagination, WithoutUrlPagination;

    // public $idsoli;
    public $cotizacionsoli = '';
    public $fechaIniciosoli = '';
    public $fechaFinsoli = '';
    public $anexo_id;
    public $anexo_coti;
    public $fechaIniciosoli2;
    public $fechaFinsoli2;

    public $readyToLoad = false;
    public function loadData()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        if ($this->readyToLoad) {
            $solicitudes = Cotizacion::where('status_cotizacion', '=', 3)
                ->when($this->cotizacionsoli, function ($query) {
                    $query->where('id', $this->cotizacionsoli);
                })
                ->when($this->fechaIniciosoli, function ($query) {
                    $query->whereDate('created_at', '>=', $this->fechaIniciosoli);
                })
                ->when($this->fechaFinsoli, function ($query) {
                    $query->whereDate('created_at', '<=', $this->fechaFinsoli);
                })
                ->orderBy('id', 'DESC')->paginate(5);
            $terminadas = Anexo1::when($this->anexo_id, function ($query) {
                $query->where('id', $this->anexo_id);
            })
                ->when($this->anexo_coti, function ($query) {
                    $query->whereDate('cotizacion_id', '>=', $this->anexo_coti);
                })
                ->when($this->fechaIniciosoli2, function ($query) {
                    $query->whereDate('created_at', '>=', $this->fechaIniciosoli2);
                })
                ->when($this->fechaFinsoli2, function ($query) {
                    $query->whereDate('created_at', '<=', $this->fechaFinsoli2);
                })
                ->orderBy('id', 'DESC')->paginate(10);
        } else {
            $solicitudes = [];
            $terminadas = [];
        }
        return view('livewire.anexo1.listados-anexo1', compact('solicitudes', 'terminadas'));
    }

}
