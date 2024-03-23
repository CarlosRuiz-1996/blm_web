<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogosController extends Controller
{
    public function index(){
        return view('admin.catalogos.catalogos-index');
    }

    public function listar($op){
        return view('admin.catalogos.catalogos-listar', compact('op'));
    }
}
