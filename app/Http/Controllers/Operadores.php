<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Operadores extends Controller
{
    public function index(){
        return view('Operadores.indexOperadores');
    }
}
