<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TablaCumplimientoEnProceso extends Component
{
    public $listSolicitudes;
    public function mount()
    {
        $this->listaSolicitudes();
    }
    public function render()
    {
        return view('livewire.tabla-cumplimiento-en-proceso');
    }

    public function listaSolicitudes()
    {
        // Obtener los documentos del expediente digital actualizado
        $this->listSolicitudes = DB::table('cumplimiento as cmp')
        ->select(
            'cmp.expediente_digital_id',
            'cmp.status_cumplimiento',
            'cl.razon_social',
            'cl.id as cliente_id',
            DB::raw('DATE(exp.fecha_solicitud) as fecha_solicitud'),
            DB::raw('(SELECT COUNT(*) FROM expediente_documentos WHERE expediente_digital_id = exp.id) AS documentos_count'),
            DB::raw('(SELECT COUNT(*) FROM ctg_documentos WHERE ctg_tipo_cliente_id = cl.ctg_tipo_cliente_id) AS ctg_doc_total')
        )
        ->join('expediente_digital as exp', 'exp.id', '=', 'cmp.expediente_digital_id')
        ->join('clientes as cl', 'cl.id', '=', 'exp.cliente_id')
        ->where('cmp.status_cumplimiento', 2) // Agrega la condición WHERE
        ->get();
}
}
