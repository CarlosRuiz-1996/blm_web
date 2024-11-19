<?php

namespace App\Livewire\Rh;

use App\Models\Empleado;
use App\Models\SolicitudVacacion;
use Livewire\Component;


class HomeRh extends Component
{

    //renderiza la vista con el conteo de empleados activos e inactivos  y el conteo total para sacar el porcentaje
    public function render()
    {
        $conteos = Empleado::selectRaw('count(*) as total, sum(case when status_empleado = 1 then 1 else 0 end) as activos, sum(case when status_empleado = 0 then 1 else 0 end) as inactivos')
                            ->first();

        $conteotodosEmpleados = $conteos->total;
        $conteoEmpleados = $conteos->activos;
        $conteoEmpleadosInactivos = $conteos->inactivos;

        $percentajeActivos = round(($conteoEmpleados / $conteotodosEmpleados) * 100);
        $percentajeInactivos = round(($conteoEmpleadosInactivos / $conteotodosEmpleados) * 100);
        $solicitudesactivas = SolicitudVacacion::where('fecha_inicio', '<=', now())
        ->where('fecha_fin', '>=', now())
        ->where('status_vacaciones',1)
        ->count();

        return view('livewire.rh.home-rh', compact(
            'conteoEmpleados', 
            'conteoEmpleadosInactivos', 
            'percentajeActivos', 
            'percentajeInactivos',
            'solicitudesactivas'
        ));
    }
}
