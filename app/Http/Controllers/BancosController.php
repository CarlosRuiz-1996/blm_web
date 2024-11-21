<?php

namespace App\Http\Controllers;



class BancosController extends Controller
{
    //redireccion a vista 
    public function  index() {
        return view('bancos.bancos-index');
    }

}
