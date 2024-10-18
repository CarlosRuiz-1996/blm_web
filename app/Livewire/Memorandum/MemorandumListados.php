<?php

namespace App\Livewire\Memorandum;

use Livewire\Component;
use App\Livewire\Forms\MemorandumForm;
use App\Models\Memorandum;
use App\Models\MemorandumValidacion;
use Barryvdh\DomPDF\Facade\Pdf;

class MemorandumListados extends Component
{
    public MemorandumForm $form;
    public $idproceso;
    public $cotizacionproceso;
    public $fechaInicioproceso;
    public $fechaFinproceso;
    public function render()
    {
        $solicitudes = $this->form->getFactibilidadAll();
        $proceso = $this->form->getMemorandumValidacion();
        $terminadas = $this->form->getMemorandumTerminado();

        return view('livewire.memorandum.memorandum-listados',compact('solicitudes','proceso','terminadas'));
    }

    public function buscar()
    {
        dd($this->idproceso, $this->cotizacionproceso, $this->fechaInicioproceso, $this->fechaFinproceso);
    }
    public function generarPDF($id)
{
    // Obtener la solicitud por ID
    $solicitud = Memorandum::find($id); // Asegúrate de que esto se adapte a tu método
    $memo_servicio=$solicitud->memo_servicio;
    // Verificar si la solicitud existe
    if (!$solicitud) {
        return response()->json(['error' => 'Solicitud no encontrada.'], 404); // Manejar el error
    }

    // Establecer datos que se pasarán a la vista
    $razon_social = $solicitud->cliente->razon_social;
    $rfc_cliente = $solicitud->cliente->rfc_cliente;
    $fecha_solicitud = $solicitud->created_at->format('d-m-Y'); // Formato de fecha
    $grupo = $solicitud->grupo;
    $ctg_tipo_solicitud_id = $solicitud->tipo_solicitud->name;
    $ctg_tipo_servicio_id = $solicitud->tipo_servicio->name;
    $observaciones = $solicitud->observaciones;

    // Reviso si la sucursal se repite y las guardo en un arreglo para la vista
    $sucursales = [];
    foreach ($solicitud->memo_cotizacion->cotizacion->anexo->sucursal_servicio as $suc) {
        $sucursal = $suc->sucursal ; // Ajusta según la propiedad correcta
        if (!in_array($sucursal, $sucursales)) {
            $sucursales[] = $sucursal;
        }
    }

    // Obtener las firmas
    $firmas = MemorandumValidacion::where('memoranda_id', $id)->get(); // Asumiendo que este método ya está definido

    // Cargar la vista y pasarle los datos
    $pdf = Pdf::loadView('memorandum.pdfmemorandum', compact(
        'razon_social',
        'rfc_cliente',
        'fecha_solicitud',
        'grupo',
        'ctg_tipo_solicitud_id',
        'ctg_tipo_servicio_id',
        'observaciones',
        'sucursales',
        'firmas',
        'memo_servicio'
    ));

    // Mostrar el PDF en el navegador
    return response()->stream(function () use ($pdf) {
        echo $pdf->output();
    }, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="solicitud_' . $solicitud->id . '.pdf"',
    ]);
}
}
