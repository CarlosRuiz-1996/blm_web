<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ctg_Tipo_Cliente;
use Illuminate\Http\Request;

class ClientesActivosController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientesactivos.clientesactivosindex',compact('clientes'));
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
