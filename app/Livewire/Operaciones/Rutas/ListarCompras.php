<?php

namespace App\Livewire\Operaciones\Rutas;

use App\Models\CompraEfectivo;
use App\Models\Ruta;
use App\Models\RutaCompraEfectivo;
use Livewire\Component;

class ListarCompras extends Component
{
    public $readyToLoadModal = false;
    public $ruta;
    public function mount(Ruta $ruta = null) {
        $this->ruta = $ruta;

    }

    public function render()
    {
        return view('livewire.operaciones.rutas.listar-compras');
    }
    

    
    public $compra_detalle = [];
    public function showCompraDetail(CompraEfectivo $compra)
    {


        $this->compra_detalle = $compra;
        $this->readyToLoadModal = true;
    }
}
