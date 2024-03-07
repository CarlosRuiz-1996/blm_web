<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Factibilidad extends Controller
{
    public function index(){
        $solicitudes  = DB::select("
        SELECT cliente_id, MAX(s.created_at) as max_created_at, c.razon_social, c.rfc_cliente
        FROM sucursal s
        INNER JOIN clientes c ON c.id = s.cliente_id
        GROUP BY cliente_id, c.razon_social, c.rfc_cliente
    ");
        
        return view('seguridad.segiridad-index',compact('solicitudes'));
    }

    public function reporte(Cliente $cliente){
        return view('seguridad.reporte-create', compact('cliente'));
    }


    
}
