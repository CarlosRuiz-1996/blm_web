<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemorandumController extends Controller
{
    public function create($factibilidad){
        // $cotizacion = ['rz'=>'razon social','rfc'=>'rfcjkjkjk', 'ejecutivo'=>'JOSE MARIA HERRERA MANRIQUEZ'];
        return view('memorandum.memorandum-create',compact('factibilidad'));
    }
}
