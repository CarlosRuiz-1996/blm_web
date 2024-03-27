<?php

namespace App\Http\Controllers;

use App\Models\Anexo1 as ModelsAnexo1;
use App\Models\Cliente;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class Anexo1 extends Controller
{
    public function index(Cotizacion $cotizacion){
        
        return view('anexo1.anexo-create', compact('cotizacion'));
    }


    public function anexo_pdf(ModelsAnexo1 $anexo){
        
        // return view('anexo1.anexo-pdf', compact('anexo'));
        $pdf = new PDF();
        // $pdf->loadHTML('<h1>Contenido del PDF</h1>', compact('cotizacion')); 
        $pdf = PDF::loadView('anexo1.anexo-pdf', compact('anexo'));
        return $pdf->stream();
    }
}
