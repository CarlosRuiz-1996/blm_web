<?php

namespace App\Livewire\Operaciones\Rutas;

use App\Models\CompraEfectivo;
use App\Models\Ruta;
use App\Models\RutaCompraEfectivo;
use Livewire\Component;

class ListarCompras extends Component
{
    public $readyToLoadModal = false;
    public $ruta_compra;
    public function mount(Ruta $ruta = null) {

        
        $this->ruta_compra =  RutaCompraEfectivo::where('ruta_id',$ruta->id)->where('status_ruta_compra_efectivos','!=',5)->get();

       

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
