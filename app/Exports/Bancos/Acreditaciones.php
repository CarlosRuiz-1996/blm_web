<?php

namespace App\Exports\Bancos;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Acreditaciones implements FromView,WithStyles, ShouldAutoSize
{
    protected $acreditaciones;

    public function __construct($acreditaciones)
    {
        $this->acreditaciones = $acreditaciones;
    }
    public function view(): View
    {
        return view('exports.bancos.acreditacion', ['acreditaciones' => $this->acreditaciones]);
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
            'A2:E1000' => [
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
