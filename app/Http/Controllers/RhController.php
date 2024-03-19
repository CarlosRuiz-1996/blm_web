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
}
