<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OperacionesController extends Controller
{
    //
    public function index(){
        return view('operaciones.operacion-index');
    }

    public function ruta_create($op,$ruta = null){
        return view('operaciones.ruta-create',compact('op','ruta'));
    }
}
