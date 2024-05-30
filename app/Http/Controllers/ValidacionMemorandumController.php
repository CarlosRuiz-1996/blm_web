<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidacionMemorandumController extends Controller
{
    //
    public function validar($memorandum,$area, $admin=null){
        return view('memorandum.validacion-memorandum',compact('memorandum','area', 'admin'));
    }

    public function listar($area, $name = null, $admin = null){
        return view('memorandum.validacion-listar',compact('area','name','admin'));
    }

    
}
