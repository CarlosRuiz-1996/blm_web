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

    //funcion para crear un permiso
    public function store(Request $request){
        Permission::create([
            'name' => $request->input('permiso'),
            'guard_name'=>'web']
        );
        return redirect()->back()->with('success', 'Permiso creado con exito!');
    }


        //funcion para actualizar un permiso

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

        //funcion para eliminar un permiso

    public function destroy(Permission $permiso)
    {
        $permiso->roles()->detach();
        $permiso->delete();
        return redirect()->back()->with('success', 'Permiso eliminado con exito!');
    }
}
