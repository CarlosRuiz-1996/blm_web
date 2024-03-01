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
        return redirect()->back()->with('success', 'Permiso creado con exito!');
    }


    
    public function updated_permiso(Request $request,Permission $permiso)
    {
        
        $request->validate([
            'new_name' => 'required|string|max:255'
        ]);
        $newName = $request->input('new_name');

        // Actualiza el nombre del rol
        $permiso->name = $newName;
        $permiso->save();
    
        return redirect()->back()->with('success', 'Permiso actualizado con exito!');
        
    }


    public function destroy(Permission $permiso)
    {
        
        $permiso->delete();
        return redirect()->back()->with('success', 'Permiso eliminad con exito!');
    }
}
