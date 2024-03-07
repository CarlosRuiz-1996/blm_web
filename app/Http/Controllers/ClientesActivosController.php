<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ctg_Tipo_Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $codigo = DB::select("
            SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
            FROM ctg_cp cp 
            LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
            LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
            WHERE cp LIKE CONCAT('%', " . $cliente->cp->cp. " , '%')
        ");
        $colonia='';
        foreach ($codigo as $c) {
            if ($cliente->ctg_cp_id == $c->id) {
                $colonia = $c->colonia;
            }
        }

        $direccion_completa = 'Calle ' . $cliente->direccion.', Colonia '.$colonia. ', ' . $codigo[0]->municipio . ' ' . $codigo[0]->name;
        return view('clientesactivos.detalles',compact('cliente','direccion_completa'));

    }
    public function edit(Cliente $cliente){
        
        return view('clientesactivos.cliente-editar',compact('cliente'));
    }
    
}
