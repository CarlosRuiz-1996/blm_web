<?php

namespace App\Exports\Bancos;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompraEfectivoExport implements FromView,WithStyles, ShouldAutoSize
{
    protected $compras;
    /**
     * recibe objeto para renderizar a la vista
     * 
     * revisar documentacion de laravel excel
     * 
     */
    public function __construct($compras)
    {
        $this->compras = $compras;
    }

    /*renderiza una vista para mapear datos*/
    public function view():View
    {
        return view('exports.bancos.compra-efectivo', ['compras'=>$this->compras]);
    }

/**agrega estilos a las tablas */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para el encabezado
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '62a9d8'],
                ],
            ],
            // Estilo para todas las celdas de la tabla
            'A2:C1000' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }
}
