<?php

namespace App\Livewire\Juridico;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TablaJuridicoAtendidas extends Component
{
    public $pdfUrl;
    public $isOpen;
    public $listSolicitudes;
    public function mount()
    {
        $this->listaSolicitudes();
    }
    public function render()
    {
        return view('livewire.juridico.tabla-juridico-atendidas');
    }
    public function listaSolicitudes()
    {
        // Obtener los documentos del expediente digital actualizado
        $this->listSolicitudes = DB::table('juridico as cmp')
        ->select(
            'cmp.expediente_digital_id',
            'cmp.status_juridico',
            'cl.razon_social',
            'cmp.dictamen',
            DB::raw('cmp.id as idcump'),
            DB::raw('DATE(cmp.fecha_dictamen)as fecha_dictamen'),
            DB::raw('DATE(exp.fecha_solicitud) as fecha_solicitud'),
            DB::raw('(SELECT COUNT(*) FROM expediente_documentos WHERE expediente_digital_id = exp.id) AS documentos_count'),
            DB::raw('(SELECT COUNT(*) FROM ctg_documentos WHERE ctg_tipo_cliente_id = cl.ctg_tipo_cliente_id) AS ctg_doc_total')
        )
        ->join('expediente_digital as exp', 'exp.id', '=', 'cmp.expediente_digital_id')
        ->join('clientes as cl', 'cl.id', '=', 'exp.cliente_id')
        ->where('cmp.status_juridico', 3) // Agrega la condición WHERE
        ->get();
}

}
