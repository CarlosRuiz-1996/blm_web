<?php

namespace App\Livewire\Boveda;

use App\Models\Servicios;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
public $servicios;
    public function mount()
    {
        $this->servicios = DB::table('ruta_servicios as rs')
        ->join('servicios as ser', 'ser.id', '=', 'rs.servicio_id')
        ->join('rutas as rut', 'rut.id', '=', 'rs.ruta_id')
        ->join('ctg_servicios as ctgser', 'ctgser.id', '=', 'ser.ctg_servicios_id')
        ->join('ctg_rutas as ctgrut', 'ctgrut.id', '=', 'rut.ctg_rutas_id')
        ->select('rs.*', 'ser.*', 'rut.*', 'ctgser.*', 'ctgrut.*')
        ->get();
        
        return view('livewire.boveda.index');
    }
    public function render()
    {
        return view('livewire.boveda.index');
    }
}
