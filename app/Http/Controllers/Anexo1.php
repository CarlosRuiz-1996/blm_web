<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class Anexo1 extends Controller
{
    public function index(Cliente $cliente){
        
        return view('anexo1.anexo-create', compact('cliente'));
    }
}
