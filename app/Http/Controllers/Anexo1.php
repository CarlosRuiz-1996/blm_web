<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use Illuminate\Http\Request;

class Anexo1 extends Controller
{
    public function index(Cotizacion $cotizacion){
        
        return view('anexo1.anexo-create', compact('cotizacion'));
    }
}
