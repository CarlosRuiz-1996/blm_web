<?php

namespace App\Livewire\Rh;

use App\Models\Empleado;
use Livewire\Component;


class HomeRh extends Component
{
    public function render()
    {
        $conteoEmpleados=Empleado::where('status_empleado',1)->count();
        $conteoEmpleadosInactivos=Empleado::where('status_empleado',0)->count();
        return view('livewire.rh.home-rh',compact('conteoEmpleados','conteoEmpleadosInactivos'));
    }
}
