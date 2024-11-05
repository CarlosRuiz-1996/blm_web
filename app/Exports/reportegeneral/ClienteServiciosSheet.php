<?php

namespace App\Exports\reportegeneral;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ClienteServiciosSheet implements FromCollection, WithTitle, WithColumnWidths, WithStyles,WithDrawings,WithCustomStartCell
{
    protected $servicio;
    protected $sheetNumber;
    public $envasesCount;
    public $llavesCount;
    public $conteoEncabezado=1;

    public function __construct($servicio, $sheetNumber)
    {
        $this->servicio = $servicio;
        $this->sheetNumber = $sheetNumber;
    }

    public function collection()
    {
        $data = [];

        // Verificar si hay rutas de servicio
        if ($this->servicio->ruta_servicios->isEmpty()) {
            $data[] = [
                'ruta' => '',
                        'Monto' => '',
                        'Tipo de Servicio' => '',
                        'Papeleta' => '',
                        'Cantidad de Envases' => '',
                        'Detalles' => '',
                        'Fecha de Servicio' => '',
            ];
        } else {
            // Recorrer cada ruta de servicio
            foreach ($this->servicio->ruta_servicios as $rutaServicio) {
                $monto = $rutaServicio->monto ?? 0;
                $this->envasesCount = $rutaServicio->envases_servicios->count() == 0 ? 'Sin envases' : $rutaServicio->envases_servicios->count();
                $this->llavesCount = $rutaServicio->servicioKeys->count() == 0 ? 'Sin llaves' : $rutaServicio->servicioKeys->count();

                // Crear entrada para esta ruta
                $data[] = [
                    'ruta' => 'Ruta',
                    'Monto' => 'Monto',
                    'Tipo de Servicio' => 'Tipo de Servicio',
                    'Papeleta' => 'Papeleta',
                    'Cantidad de Envases' => 'Cantidad de Envases',
                    'Detalles' => 'Cantidad de Llaves',
                    'Fecha de Servicio' => 'Fecha de Servicio',
                    'Sucursal' => 'Sucursal',
                ];
                $data[] = [
                    'ruta' => $rutaServicio->ruta->nombre->name ?? 'Sin ruta',
                    'Monto' => $monto,
                    'Tipo de Servicio' => $rutaServicio->tipo_servicio == 2 ? 'Recolección' : 'Entrega',
                    'Papeleta' => $rutaServicio->folio =='' ? 'Sin folio':$rutaServicio->folio,
                    'Cantidad de Envases' => $this->envasesCount,
                    'Detalles' => $this->llavesCount,
                    'Fecha de Servicio' => $rutaServicio->fecha_servicio ? \Carbon\Carbon::parse($rutaServicio->fecha_servicio)->format('d/m/Y') : 'Sin fecha asignada',
                    'Sucursal' => $rutaServicio->servicio->sucursal->sucursal->sucursal ?? 'Sin sucursal',
                ];

                // Agregar sección de llaves
                if ($this->llavesCount !='Sin llaves') {
                    $data[] = [
                        'ruta' => 'Llaves Detalle',
                        'Monto' => '',
                        'Tipo de Servicio' => '',
                        'Papeleta' => '',
                        'Cantidad de Envases' => '',
                        'Detalles' => '',
                        'Fecha de Servicio' => '',
                    ];

                    foreach ($rutaServicio->servicioKeys as $llave) {
                        $data[] = [
                            'ruta' => $llave->key,
                            'Monto' => '',
                            'Tipo de Servicio' => '',
                            'Papeleta' => '',
                            'Cantidad de Envases' => '',
                            'Detalles' => '',
                            'Fecha de Servicio' => '',
                            'Sucursal' => '',
                        ];
                    }
                }

                // Agregar sección de envases
                if ($this->envasesCount !='Sin envases') {
                    $data[] = [
                            'ruta' => 'Envases Detalle',
                            'Monto' => '',
                            'Tipo de Servicio' => '',
                            'Papeleta' => '',
                            'Cantidad de Envases' => '',
                            'Detalles' => '',
                            'Fecha de Servicio' => '',
                            'Sucursal' => '',
                    ];
                    $data[] = [
                        'ruta' => 'Cantidad',
                        'Monto' => 'Folio',
                        'Tipo de Servicio' => 'Sello de Seguridad',
                        'Papeleta' => '',
                            'Cantidad de Envases' => '',
                            'Detalles' => '',
                            'Fecha de Servicio' => '',
                            'Sucursal' => '',
                    ];
                    
                    foreach ($rutaServicio->envases_servicios as $envase) {
                        $data[] = [
                            'ruta' => $envase->cantidad,
                            'Monto' => $envase->cantidad,
                            'Tipo de Servicio' => $envase->sello_seguridad,
                            'Papeleta' => '',
                            'Cantidad de Envases' => '',
                            'Detalles' => '',
                            'Fecha de Servicio' => '',
                            'Sucursal' => '',
                        ];
                    }
                }

                // Agregar fila vacía después de cada ruta
                $data[] = [
                    'ruta' => '',
                    'Monto' => '',
                    'Tipo de Servicio' => '',
                    'Papeleta' => '',
                    'Cantidad de Envases' => '',
                    'Detalles' => '',
                    'Fecha de Servicio' => '',
                    'Sucursal' => '',
                ];
                $data[] = [
                    'ruta' => '',
                    'Monto' => '',
                    'Tipo de Servicio' => '',
                    'Papeleta' => '',
                    'Cantidad de Envases' => '',
                    'Detalles' => '',
                    'Fecha de Servicio' => '',
                    'Sucursal' => '',
                ];
            }
        }

        return collect($data);
    }

    public function title(): string
    {
        return $this->servicio->ctg_servicio->descripcion.' '.$this->servicio->sucursal->sucursal->sucursal ?? "Servicio {$this->sheetNumber}";
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 30,
            'C' => 30,
            'D' => 30,
            'E' => 40,
            'F' => 30,
            'G' => 40,
            'H' => 40,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        // Combinar las celdas desde A1 hasta H3
        $sheet->mergeCells('A1:H2'); // Combina para "Reporte general de servicios"
        $sheet->mergeCells('A3:H3'); // Combina para "Razón Social"
    
        // Establecer el texto en cada celda combinada
        $sheet->setCellValue('A1', 'Reporte general de servicios');
        $sheet->setCellValue('A3', $this->servicio->cliente->razon_social);
    
        // Centrando ambos textos
        $sheet->getStyle('A1:H2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:H2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A3:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:H3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
        // Quitar bordes en las celdas combinadas
        $sheet->getStyle('A1:H2')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
        $sheet->getStyle('A3:H3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
    
        // Establecer fondo blanco en las celdas combinadas
        $sheet->getStyle('A1:H2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H2')->getFill()->getStartColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:H3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A3:H3')->getFill()->getStartColor()->setRGB('FFFFFF');

        $sheet->getStyle('A4:H4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0070C0'], // Color de fondo del encabezado
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Color negro
                ],
            ],
        ]);
    
        // Unir celdas para las secciones de detalles y aplicar estilos
        $rowCount = $sheet->getHighestRow();
        for ($row = 5; $row <= $rowCount; $row++) {
            $cellValue = trim($sheet->getCell("A{$row}")->getValue());
             // Obtener y normalizar el valor de la celda
             $cellValueB = trim($sheet->getCell("B{$row}")->getValue());
             $cellValueC = trim($sheet->getCell("C{$row}")->getValue());
             $cellValueD = trim($sheet->getCell("D{$row}")->getValue());
             $cellValueE = trim($sheet->getCell("E{$row}")->getValue());
             $cellValueF = trim($sheet->getCell("F{$row}")->getValue());
             $cellValueG = trim($sheet->getCell("G{$row}")->getValue());
             $cellValueH = trim($sheet->getCell("H{$row}")->getValue());
            if ($cellValue === 'Llaves Detalle' || $cellValue === 'Envases Detalle') {
                $sheet->mergeCells("A{$row}:H{$row}"); // Combina las columnas A a F
                // Aplicar estilo a las celdas combinadas
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => Color::COLOR_BLACK], // Color de texto blanco
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFA7D4E0'], // Color de fondo para la sección
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Color negro
                        ],
                    ],
                ]);
            } elseif ($cellValue === '') {
                // Aplicar estilo a la fila vacía
                $sheet->mergeCells("A{$row}:H{$row}"); // Combina las columnas A a F
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFFFFFF'], // Color de fondo blanco
                    ],
                ]);
            } elseif($cellValue === 'Ruta' && $cellValueB === 'Monto' && $cellValueC === 'Tipo de Servicio'
             && $cellValueD === 'Papeleta' && $cellValueE === 'Cantidad de Envases' && $cellValueF === 'Cantidad de Llaves'
             && $cellValueG === 'Fecha de Servicio' && $cellValueH === 'Sucursal') {
                // Aplicar estilos a la fila de encabezado

                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray($this->getHeaderStyle());
            }elseif($cellValue === 'Cantidad' && $cellValueB === 'Folio' && $cellValueC === 'Sello de Seguridad'){
                $sheet->mergeCells("A{$row}:B{$row}");
                $sheet->mergeCells("C{$row}:D{$row}");
                $sheet->mergeCells("E{$row}:H{$row}");

                // Establecer el valor en la celda que se mostrará después de la fusión
                $sheet->setCellValue("A{$row}", 'Cantidad');
                $sheet->setCellValue("C{$row}", 'Folio');
                $sheet->setCellValue("E{$row}", 'Sello de Seguridad');

                // Aplicar el estilo a las celdas fusionadas
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray($this->getHeaderStyle2());
                $sheet->getStyle("C{$row}:E{$row}")->applyFromArray($this->getHeaderStyle2());
                $sheet->getStyle("F{$row}:H{$row}")->applyFromArray($this->getHeaderStyle2());
            }elseif(($cellValue !== '' && $cellValueB !== '' && $cellValueC !== '') && ($cellValueD === '' && $cellValueE === '' && $cellValueF === ''  && $cellValueG === ''  && $cellValueH === '')){
                $sheet->mergeCells("A{$row}:B{$row}");
                $sheet->mergeCells("C{$row}:D{$row}");
                $sheet->mergeCells("E{$row}:H{$row}");
                // Establecer el valor en la celda que se mostrará después de la fusión
                $sheet->setCellValue("A{$row}", $cellValue);
                $sheet->setCellValue("C{$row}", $cellValueB);
                $sheet->setCellValue("E{$row}", $cellValueC);
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF0F0F0'], // Color gris claro
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Color negro
                        ],
                    ],
                ]);
            }elseif($cellValue !== '' && $cellValueB === '' && $cellValueC === '' && $cellValueD === '' && $cellValueE === '' && $cellValueF === ''&& $cellValueG === '' && $cellValueH === ''){
                $sheet->mergeCells("A{$row}:H{$row}");
                // Establecer el valor en la celda que se mostrará después de la fusión
                $sheet->setCellValue("A{$row}", $cellValue);
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF0F0F0'], // Color gris claro
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Color negro
                        ],
                    ],
                ]);
            }else {
                // Aplicar color gris a las celdas que no están combinadas (solo para datos)
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF0F0F0'], // Color gris claro
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Color negro
                        ],
                    ],
                ]);
            }
        }
    }
        
    private function getHeaderStyle()
    {
        return [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0070C0'], // Color de fondo del encabezado
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Color negro
                ],
            ],
        ];
    }
    private function getHeaderStyle2()
    {
        return [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFA7D4E0'], // Color de fondo del encabezado
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Color negro
                ],
            ],
        ];
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Encabezado');
        $drawing->setDescription('Encabezado de la hoja');
        $drawing->setPath(public_path('img/logo_blm.png')); // Ruta de la imagen
        $drawing->setHeight(50); // Ajusta el tamaño de la imagen
        $drawing->setCoordinates('C1'); // Ubicación inicial de la imagen (celda superior izquierda)
    
        // Ajustes para centrar la imagen en el rango A1:H3
        $drawing->setOffsetX(550); // Ajuste horizontal (cámbialo según necesites)
        $drawing->setOffsetY(3);  // Ajuste vertical (cámbialo según necesites)
    
        return [$drawing];
    }    
    public function startCell(): string
    {
        return 'A4';
    }
}
