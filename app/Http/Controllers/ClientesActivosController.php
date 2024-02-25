<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientesActivosController extends Controller
{
    public function index()
    {
        return view('clientesactivos.clientesactivosindex');
    }
    public function nuevousuario()
    {
        return view('clientesactivos.clientesnuevos');

    }
    public function clienteCotizaciones()
    {
        return view('clientesactivos.clienteCotizaciones');

    }
    
}
