<?php

namespace App\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClienteCabecera extends Component
{
    public $cliente;
    public $direccion_completa;
    public function mount(Cliente $cliente){
        $this->cliente = $cliente;
        $codigo = DB::select("
        SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
        FROM ctg_cp cp 
        LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
        LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
        WHERE cp LIKE CONCAT('%', '" . $cliente->cp->cp. "' , '%')
    ");
    $colonia='';
    foreach ($codigo as $c) {
        if ($cliente->ctg_cp_id == $c->id) {
            $colonia = $c->colonia;
        }
    }

    $this->direccion_completa = 'Calle ' . $cliente->direccion.', Colonia '.$colonia. ', '.$cliente->cp->cp.' ' . $codigo[0]->municipio . ' ' . $codigo[0]->name;
    }
    public function render()
    {
        return view('livewire.cliente-cabecera');
    }
}
