<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles-permisos.gestion-roles',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        Role::create(['name'=>$request->input('rol')]);
        
        return redirect()->back()->with('success', 'Rol se creo con exito!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $permissions = $role->permissions;
        return response()->json(['permissions' => $permissions]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        $permisos = Permission::all();
        return view('admin.roles-permisos.rol-permisos',compact('role','permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
      
        //asigna los permisos a los roles
        $role->permissions()->sync($request->permisos);
        return redirect()->route('roles.edit',$role);
    }


    public function updated_rol(Request $request,Role $role)
    {
        
        $request->validate([
            'new_name' => 'required|string|max:255'
        ]);
        $newName = $request->input('new_name');

        // Actualiza el nombre del rol
        $role->name = $newName;
        $role->save();
    
        return redirect()->back()->with('success', 'Rol actualizado con exito!');
        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Rol eliminad con exito!');
    }
}
