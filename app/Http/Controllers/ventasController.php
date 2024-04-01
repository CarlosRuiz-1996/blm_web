<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ventasController extends Controller
{
    public function indexventas()
    {
        return view('ventas.ventasindex');
    }
    public function altaSolicitudCumplimiento($cliente)
    {
        return view('ventas.altaSolicitudCumplimiento',compact('cliente'));
    }

    public function expediente_digital($cliente,$sts)
    {
        return view('ventas.altaSolicitudCumplimiento',compact('cliente','sts'));
    }
}
