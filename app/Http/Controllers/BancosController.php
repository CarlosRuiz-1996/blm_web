<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BancosController extends Controller
{
    //
    public function  index() {
        return view('bancos.bancos-index');
    }
}
