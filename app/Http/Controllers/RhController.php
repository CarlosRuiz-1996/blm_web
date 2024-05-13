<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RhController extends Controller
{
    public function index(){
       
        return view('rh.rhindex');
    }

    public function altaempleado(){
       
        return view('rh.altaempleado');
    }
    public function indexVacaciones(){
       
        return view('rh.vacaciones');
    }
    public function solicitudVacaciones(){
       
        return view('rh.solicitud-vacaciones');
    }
}
