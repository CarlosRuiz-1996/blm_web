<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class Factibilidad extends Controller
{
    public function index(){
        return view('seguridad.segiridad-index');
    }

    public function reporte(Cliente $cliente){
        return view('seguridad.reporte-create', compact('cliente'));
    }


    
}
