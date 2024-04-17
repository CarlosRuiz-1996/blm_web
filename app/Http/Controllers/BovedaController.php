<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BovedaController extends Controller
{
     public function index(){
        return view('boveda.bovedaindex');
    }
}
