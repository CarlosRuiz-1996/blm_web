<?php

namespace App\Http\Controllers;

use App\Models\Anexo1 as ModelsAnexo1;
use App\Models\Cliente;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class Anexo1 extends Controller
{

    //redirecciona a la vista de de anexo 1
    public function index(Cotizacion $cotizacion){
        
        return view('anexo1.anexo-create', compact('cotizacion'));
    }

    //genera un pdf con dompdf, recibe datos del modelo de anexo1 y los pasa a la vista para mapearlo
    public function anexo_pdf(ModelsAnexo1 $anexo){
        
        $pdf = new PDF();
        $pdf = PDF::loadView('anexo1.anexo-pdf', compact('anexo'));
        return $pdf->stream();
    }
}
