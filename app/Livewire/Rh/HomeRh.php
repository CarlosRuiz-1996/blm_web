<?php

namespace App\Livewire\Rh;

use App\Models\Empleado;
use Livewire\Component;


class HomeRh extends Component
{
    public function render()
    {
        $conteos = Empleado::selectRaw('count(*) as total, sum(case when status_empleado = 1 then 1 else 0 end) as activos, sum(case when status_empleado = 0 then 1 else 0 end) as inactivos')
                            ->first();

        $conteotodosEmpleados = $conteos->total;
        $conteoEmpleados = $conteos->activos;
        $conteoEmpleadosInactivos = $conteos->inactivos;

        $percentajeActivos = round(($conteoEmpleados / $conteotodosEmpleados) * 100);
        $percentajeInactivos = round(($conteoEmpleadosInactivos / $conteotodosEmpleados) * 100);

        return view('livewire.rh.home-rh', compact(
            'conteoEmpleados', 
            'conteoEmpleadosInactivos', 
            'percentajeActivos', 
            'percentajeInactivos'
        ));
    }
}
