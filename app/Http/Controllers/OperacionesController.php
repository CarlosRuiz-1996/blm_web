<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OperacionesController extends Controller
{
    //
    public function index(){
        return view('operaciones.operacion-index');
    }

    public function ruta_gestion($op,$ruta = null){
        return view('operaciones.ruta-gestion',compact('op','ruta'));
    }
}
