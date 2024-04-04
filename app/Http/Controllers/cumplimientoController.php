<?php

namespace App\Http\Controllers;

use App\Models\cumplimiento;
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
        ->where('ca.cumplimiento_id', $id)
        ->get();
        $razonSocial = cumplimiento::select('clientes.razon_social')
            ->join('expediente_digital', 'expediente_digital.id', '=', 'cumplimiento.expediente_digital_id')
            ->join('clientes', 'clientes.id', '=', 'expediente_digital.cliente_id')
            ->where('cumplimiento.id', $id)
            ->first();
        $razonSocial = $razonSocial->razon_social;
        $pdf=Pdf::loadView('cumplimiento.pdfdictamencumplimiento',compact('cumplimentovalidado','razonSocial'));
        return $pdf->stream('aceptado.pdf');
    }
    public function pdfcumplimientonegado($id)
    {

        $cumplimentovalidado= DB::table('cumplimiento_rechazo as ca')
        ->join('ctg_rechazo as ctgace', 'ca.ctg_rechazo_id', '=', 'ctgace.id')
        ->select('ca.status_cumplimiento_rechazo', 'ctgace.name')
        ->where('ca.cumplimiento_id', $id)
        ->get();
        $razonSocial = cumplimiento::select('clientes.razon_social')
            ->join('expediente_digital', 'expediente_digital.id', '=', 'cumplimiento.expediente_digital_id')
            ->join('clientes', 'clientes.id', '=', 'expediente_digital.cliente_id')
            ->where('cumplimiento.id', $id)
            ->first();
            $razonSocial = $razonSocial->razon_social;
        $pdf=Pdf::loadView('cumplimiento.pdfdictamencumplimientonegado',compact('cumplimentovalidado','razonSocial'));
        return $pdf->stream('negadopdf.pdf');
    }


    public function validacion()
    {
        return view('cumplimiento.altaValidaCumplimiento');
    }
}

