<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{

    //redireccion a vista y pasa parametros
    public function index(){
        $bitacoras = Bitacora::orderBy('id','desc')->get();
        return view('admin.bitacora.bitacora-index',compact('bitacoras'));
    }

}
