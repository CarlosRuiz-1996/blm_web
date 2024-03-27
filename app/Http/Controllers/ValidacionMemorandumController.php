<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidacionMemorandumController extends Controller
{
    //
    public function validar($memorandum,$area){
        return view('memorandum.validacion-memorandum',compact('memorandum','area'));
    }

    public function listar($area, $name = null){
        return view('memorandum.validacion-listar',compact('area','name'));
    }
}
