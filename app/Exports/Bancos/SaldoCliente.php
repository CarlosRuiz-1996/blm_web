<?php

namespace App\Exports\Bancos;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Cliente;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SaldoCliente implements FromView, WithStyles, ShouldAutoSize
{
    protected $clientes;
    public function __construct($clientes)
    {
        $this->clientes = $clientes;
    }

    public function view(): View
    {
        return view('exports.bancos.saldo-clientes', ['clientes' => $this->clientes]);
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
