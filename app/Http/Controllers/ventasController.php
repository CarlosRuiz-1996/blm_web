<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ventasController extends Controller
{
    public function indexventas()
    {
        return view('ventas.ventasindex');
    }
    public function altaSolicitudCumplimiento()
    {
        return view('ventas.altaSolicitudCumplimiento');
    }
}
