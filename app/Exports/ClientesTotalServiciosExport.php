<?php

namespace App\Exports;

use App\Models\Cliente;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class ClientesTotalServiciosExport implements FromView, ShouldAutoSize
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $razonsocial;

    public function __construct($fechaInicio, $fechaFin, $razonsocial)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->razonsocial = $razonsocial;
    }

    public function view(): View
    {
        $clientes = Cliente::whereHas('servicios.ruta_servicios', function ($query) {
            // Filtrar las rutas por el rango de fechas
            $query->whereBetween('ruta_servicios.created_at', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        })
        ->with(['servicios.ruta_servicios' => function ($query) {
            // Cargar rutas que cumplen con el rango de fechas para la vista
            $query->whereBetween('ruta_servicios.created_at', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }])
        // Filtrar por razón social si se proporciona
        ->when(!empty($this->razonsocial), function ($query) {
            $query->where('razon_social', 'like', '%' . $this->razonsocial . '%');
        })
        ->get();

        return view('exports.clientesServiciosTotales.servicios-totales-excel', compact('clientes'));
    }
}
