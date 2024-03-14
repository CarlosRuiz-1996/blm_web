<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cumplimientoController extends Controller
{
    public function index()
    {
        return view('cumplimiento.indexcumplimiento');
    }

    public function pdfcumplimiento($id)
    {

        $cumplimentovalidado= DB::table('cumplimiento_aceptado as ca')
        ->join('ctg_aceptado as ctgace', 'ca.ctg_aceptado_id', '=', 'ctgace.id')
        ->select('ca.status_cumplimiento_aceptado', 'ctgace.name')
        ->where('ca.cumplimiento_id', 4)
        ->get();
        $pdf=Pdf::loadView('cumplimiento.pdfdictamencumplimiento',compact('cumplimentovalidado'));
        return $pdf->stream('aceptado.pdf');
    }
}
