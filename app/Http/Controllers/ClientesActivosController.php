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
    public function detalles(Cliente $cliente)
    {
        return view('clientesactivos.detalles',compact('cliente'));

    }
    public function CotizacionesNuevas()
    {
        return view('clientesactivos.clienteCotizaciones');

    }
    public function edit(Cliente $cliente){
        
        return view('clientesactivos.cliente-editar',compact('cliente'));
    }
    public function cotizardenuevo($id)
    {
        // Aquí puedes utilizar el valor de $id en tu lógica
        return view('clientesactivos.cotizardenuevo', ['id' => $id]);
    }
    
}
