<?php

namespace App\Http\Controllers\reportegeneral;

use App\Exports\reportegeneral\reportegeneralClienteExport;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class reporteGeneral extends Controller
{
    public function index()
    {
        return view('reportegeneral.reporteGeneral');
    }
    public function descargarPdf($id)
    {
        $cliente = Cliente::find($id);
        $pdf = Pdf::loadView('pdfreportegeneral.cliente', compact('cliente'));
        return $pdf->download('servicio_' . $id . '.pdf');
    }

    public function descargarExcel($id)
    {
        // Lógica para generar el archivo Excel
        return Excel::download(new reportegeneralClienteExport($id), 'servicio_' . $id . '.xlsx');
    }
}
