<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class juridicoController extends Controller
{
    public function index()
    {
        return view('juridico.juridico');
    }

    public function validajuridico()
    {
        return view('juridico.altaValidaJuridico');
    }
}
