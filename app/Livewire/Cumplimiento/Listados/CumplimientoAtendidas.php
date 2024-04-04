<?php

namespace App\Livewire\Cumplimiento\Listados;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CumplimientoAtendidas extends Component
{
    public function render()
    {
        return view('livewire.cumplimiento.listados.cumplimiento-atendidas');
    }

    public $pdfUrl;
    public $isOpen;
    public $listSolicitudes;
    public function mount()
    {
        $this->listaSolicitudes();
    }
   

    public function listaSolicitudes()
    {
        // Obtener los documentos del expediente digital actualizado
        $this->listSolicitudes = DB::table('cumplimiento as cmp')
        ->select(
            'cmp.expediente_digital_id',
            'cmp.status_cumplimiento',
            'cl.razon_social',
            'cl.id',
            'cmp.dictamen',
            DB::raw('cmp.id as idcump'),
            DB::raw('DATE(cmp.fecha_dictamen)as fecha_dictamen'),
            DB::raw('DATE(exp.fecha_solicitud) as fecha_solicitud'),
            DB::raw('(SELECT COUNT(*) FROM expediente_documentos WHERE expediente_digital_id = exp.id) AS documentos_count'),
            DB::raw('(SELECT COUNT(*) FROM ctg_documentos WHERE ctg_tipo_cliente_id = cl.ctg_tipo_cliente_id) AS ctg_doc_total'),
            DB::raw('(SELECT COUNT(*) FROM expediente_documentos_benf WHERE expediente_digital_id = exp.id) AS documentosbene_count'),
            DB::raw('(SELECT COUNT(*) FROM ctg_documentos_benf ) AS ctg_docbene_total'),
        )
        ->join('expediente_digital as exp', 'exp.id', '=', 'cmp.expediente_digital_id')
        ->join('clientes as cl', 'cl.id', '=', 'exp.cliente_id')
        ->where('cmp.status_cumplimiento', 3) // Agrega la condición WHERE
        ->get();
}

public function openModal($pdfUrl)
{
    $this->pdfUrl = $pdfUrl;
    $this->isOpen = true;
}

public function closeModal()
{
    $this->pdfUrl = "";
    $this->isOpen = false;
}
}
