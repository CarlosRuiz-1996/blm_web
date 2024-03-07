<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemorandumController extends Controller
{
    public function create($cotizacion){
        $cotizacion = ['rz'=>'razon social','rfc'=>'rfcjkjkjk', 'ejecutivo'=>'JOSE MARIA HERRERA MANRIQUEZ'];
        return view('memorandum.create-memorandum',compact('cotizacion'));
    }
}
