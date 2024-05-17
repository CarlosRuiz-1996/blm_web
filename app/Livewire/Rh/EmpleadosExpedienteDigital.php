<?php

namespace App\Livewire\Rh;

use App\Models\CtgDocumentoExpedienteEmpleado;
use App\Models\DocumentoEmpleado;
use Livewire\Component;

class EmpleadosExpedienteDigital extends Component
{
    public $empleadoId;
    public function render()
    {
        $tiposDocumentos = CtgDocumentoExpedienteEmpleado::all();
        $documentosPorEmpleado = DocumentoEmpleado::where('empleado_id',$this->empleadoId)->get();
        return view('livewire.rh.empleados-expediente-digital', compact('tiposDocumentos','documentosPorEmpleado'));
    }
}
