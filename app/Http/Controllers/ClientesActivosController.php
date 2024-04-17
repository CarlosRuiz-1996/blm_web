<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Ctg_Tipo_Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientesActivosController extends Controller
{
    public function index()
    {
        $clientes = Cliente::where('status_cliente',1)->get();
        return view('clientesactivos.clientesactivosindex',compact('clientes'));
    }
    public function nuevousuario()
    {
       
        return view('clientesactivos.clientesnuevos');

    }
    public function detalles(Cliente $cliente, $op)
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
        return view('clientesactivos.detalles',compact('cliente','direccion_completa','op'));

    }
    public function CotizacionesNuevas()
    {
        return view('clientesactivos.nuevacotizacion');

    }
    public function edit(Cliente $cliente){
        
        return view('clientesactivos.cliente-editar',compact('cliente'));
    }
    public function cotizardenuevo($id)
    {
        // Aquí puedes utilizar el valor de $id en tu lógica
        return view('clientesactivos.cotizardenuevo', ['id' => $id]);
    }
    

    public function detalle_cotizacion( Cotizacion $cotizacion){
        return view('ventas.cotizacion-detalles', compact('cotizacion'));
    }
    
    public function cotizacion_pdf(Cotizacion $cotizacion){
        $pdf = new PDF();
        // $pdf->loadHTML('<h1>Contenido del PDF</h1>', compact('cotizacion')); 
        $pdf = PDF::loadView('ventas.cotizacion-pdf', compact('cotizacion'));
        return $pdf->stream();
    }
}
