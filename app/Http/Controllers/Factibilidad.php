<?php

namespace App\Http\Controllers;

use App\Models\Anexo1;

use Barryvdh\DomPDF\Facade\Pdf;

class Factibilidad extends Controller
{
    public function index()
    {
        $solicitudes  = Anexo1::where('status_anexo', 1)->get();
        $procesos  = Anexo1::where('status_anexo', 2)->get();
        $terminados  = Anexo1::where('status_anexo', 3)->get();

        return view('seguridad.segiridad-index', compact('solicitudes', 'procesos', 'terminados'));
    }

    public function reporte(Anexo1 $anexo)
    {

        return view('seguridad.reporte-create', compact('anexo'));
    }


    //ejemplo de como genera un pdf desde un controller, este fue remplazado por una funcion en livewire/factibilidad.php
    public function showPDF()
    {
        $data = [
            'title' => 'Ejemplo de PDF',
            'content' => 'Este es un ejemplo de contenido para el PDF.',
        ];

        $pdf = Pdf::loadView('seguridad.reporte-pdf', $data);

        // return $pdf->download('ejemplo.pdf');
        return $pdf->stream('ejemplo.pdf');

    }
}
