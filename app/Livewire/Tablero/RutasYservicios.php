<?php

namespace App\Livewire\Tablero;

use App\Models\Ruta;
use App\Models\Servicios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RutasYservicios extends Component
{



    public function getListeners()
    {
        $empleado_id = Auth::user()->empleado->id;
        return [
            // Private Channel
            // "echo:notification.{$empleado_id},notification" => 'render'
            "echo-notification:App.Models.Empleado.{$empleado_id},notification" => 'render',
        ];
    }

    public function render()
    {
        $dia=$this->obtenerDia();
        $rutaEmpleados = Ruta::where('ctg_ruta_dia_id', 3)
        ->whereIn('ctg_rutas_estado_id', [3, 4])
        ->get();
        //dd($rutaEmpleados);
        return view('livewire.tablero.rutas-yservicios',compact('rutaEmpleados'));
    }

    public function obtenerservicio(){
    }

    public function obtenerDia(){
        $dayOfWeek = Carbon::now()->dayOfWeek; // Obtiene el día de la semana (0 para Domingo, 1 para Lunes, etc.)
        $id = 0;
        switch ($dayOfWeek) {
            case 1: // Lunes
                $id = 1;
                break;
            case 2: // Martes
                $id = 2;
                break;
            case 3: // Miércoles
                $id = 3;
                break;
            case 4: // Jueves
                $id = 4;
                break;
            case 5: // Viernes
                $id = 5;
                break;
            case 6: // Sábado
                $id = 6;
                break;
            case 0: // Domingo
                $id = 7;
                break;
        }
        return $id;
    }
}
