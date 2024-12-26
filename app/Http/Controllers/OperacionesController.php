<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Ruta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OperacionesController extends Controller
{
    //
    public function index()
    {
        return view('operaciones.operacion-index');
    }

    public function ruta_gestion($op, $ruta = null)
    {
        return view('operaciones.ruta-gestion', compact('op', 'ruta'));
    }

    public function hoja_ruta(Ruta $ruta)
    {
        // $operador = Empleado::where('ctg_area_id', 18)
        //     ->join('ruta_empleados as re', 'empleados.id', '=', 're.empleado_id')
        //     ->where('re.id', $ruta->id)
        //     ->get();

        
        $pdf = new PDF();
        $pdf = PDF::loadView('operaciones.ruta-hoja', compact('ruta'))->setPaper('a4', 'landscape');;
        return $pdf->stream();
    }
}
