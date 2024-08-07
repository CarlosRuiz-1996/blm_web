<?php

namespace App\Http\Controllers;

use App\Models\Inconsistencias;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BovedaController extends Controller
{
     public function index(){
        return view('boveda.bovedaindex');
    }
    public function bovedaresguardo(){
        return view('boveda.boveda-resguardo');
    }

    public function acta_diferencia(Inconsistencias $diferencia){

        $pdf = new PDF();
        $pdf = PDF::loadView('boveda.acta_diferencia-pdf', compact('diferencia'));
        return $pdf->stream();
    }


    public function procesa_ruta(Ruta $ruta){
        return view('boveda.procesar-ruta', compact('ruta'));
    }

    public function cambio_efectivo(){
        return view('boveda.cambio-efectivo');
    }
}
