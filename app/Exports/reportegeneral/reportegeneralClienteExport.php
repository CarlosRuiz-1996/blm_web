<?php

namespace App\Exports\reportegeneral;

use App\Models\Cliente;
use App\Models\Servicios;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class reportegeneralClienteExport implements WithMultipleSheets
{
    protected $id;
    protected $fechaInicio;
    protected $fechaFin;
    protected $limit = 10; // Número máximo de servicios por hoja

    public function __construct($id, $fechaInicio, $fechaFin)
    {
        $this->id = $id;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // Obtener los servicios del cliente aplicando el filtro de fechas
        $servicios =  Servicios::where('cliente_id', $this->id)
        ->when($this->fechaInicio && $this->fechaFin, function ($query) {
            $query->whereHas('ruta_servicios', function ($query) {
                $query->whereBetween('fecha_servicio', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            });
        })
        ->with(['ruta_servicios' => function ($query) {
            // Aplicar filtro de fechas para las rutas cargadas en la relación
            if ($this->fechaInicio && $this->fechaFin) {
                $query->whereBetween('fecha_servicio', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            }
        }])
        ->get();

        // Crear una hoja para cada servicio filtrado
        foreach ($servicios as $index => $servicio) {
            $sheets[] = new ClienteServiciosSheet($servicio, $index + 1);
        }

        return $sheets;
    }
}
