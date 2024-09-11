<?php

namespace App\Exports\Bancos;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiciosBancos implements FromView, ShouldAutoSize,  WithStyles

{
    protected $servicios;

    public function __construct($servicios)
    {
        $this->servicios = $servicios;
    }

    public function view(): View
    {
        return view('exports.bancos.servicios-bancos', ['servicios' => $this->servicios]);
    }
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
            'A2:D1000' => [
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
