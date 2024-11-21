<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogosController extends Controller
{
    public function index(){
        return view('admin.catalogos.catalogos-index');
    }


    //redirecciona a una vista y pasa un parametro que recibe por url/get
    public function listar($op){
        return view('admin.catalogos.catalogos-listar', compact('op'));
    }
}
