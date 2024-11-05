<?php

namespace App\Http\Controllers\reportegeneral;

use App\Exports\reportegeneral\reportegeneralClienteExport;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class reporteGeneral extends Controller
{
    public function index()
    {
        return view('reportegeneral.reporteGeneral');
    }
    public function descargarPdf($id, $fechaInicio, $fechaFin)
    {
        // Obtener el cliente y filtrar sus servicios y las rutas de esos servicios por las fechas
        $cliente = Cliente::with(['servicios' => function ($query) use ($fechaInicio, $fechaFin) {
            // Filtrar los servicios que tienen rutas en el rango de fechas
            $query->whereHas('ruta_servicios', function ($query) use ($fechaInicio, $fechaFin) {
                if ($fechaInicio && $fechaFin) {
                    $query->whereBetween('fecha_servicio', [
                        Carbon::parse($fechaInicio)->startOfDay(),
                        Carbon::parse($fechaFin)->endOfDay()
                    ]);
                }
            });
        }, 'servicios.ruta_servicios' => function ($query) use ($fechaInicio, $fechaFin) {
            // Filtrar las rutas de los servicios en el rango de fechas
            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('fecha_servicio', [
                    Carbon::parse($fechaInicio)->startOfDay(),
                    Carbon::parse($fechaFin)->endOfDay()
                ]);
            }
        }])->find($id);
    
        // Verifica que el cliente y sus servicios filtrados existan
        if (!$cliente || $cliente->servicios->isEmpty()) {
            return response()->json(['message' => 'No se encontraron servicios en el rango de fechas.'], 404);
        }
    
        // Carga la vista del PDF con el cliente y sus servicios filtrados
        $pdf = Pdf::loadView('pdfreportegeneral.cliente', compact('cliente'));
    
        // Descarga el PDF
        return $pdf->download('servicio_' . $id . '.pdf');
    }
    
    
    
    


    public function descargarExcel($id, $fechaInicio, $fechaFin)
    {
        // Puedes usar las fechas como quieras aquí, o pasarlas al exportador
        return Excel::download(new reportegeneralClienteExport($id, $fechaInicio, $fechaFin), 'servicio_' . $id . '.xlsx');
    }
    
}
