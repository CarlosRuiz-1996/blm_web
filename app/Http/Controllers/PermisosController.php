<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisosController extends Controller
{
    //
    public function index(){
        $permisos = Permission::all();
        return view('admin.roles-permisos.gestion-permisos',compact('permisos'));
    }

    public function store(Request $request){

        Permission::create(['name' => $request->input('permiso')]);
       return back();
    }
}
