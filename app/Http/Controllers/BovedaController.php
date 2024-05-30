<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BovedaController extends Controller
{
     public function index(){
        return view('boveda.bovedaindex');
    }
    public function bovedaresguardo(){
        return view('boveda.boveda-resguardo');
    }
}
