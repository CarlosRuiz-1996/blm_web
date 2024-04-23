<?php

namespace App\Http\Controllers;

use App\Models\Ctg_Area;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index()
    {
        // $users = User::orderBy('id', 'desc')->get();
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Cliente');
        })->orderBy('id', 'desc')->get();

        return view('usuarios.usuariosindex', compact('users'));
    }
    public function nuevousuario()
    {
        $areas = Ctg_Area::all();
        $roles = Role::all();
        return view('usuarios.usuarionuevo', compact('areas', 'roles'));
    }
    public function store(Request $request)
    {
        $validate = $this->validate($request, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'paterno' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'materno' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'roles' => ['array'],
        ]);
        // dd($request->input('roles'));
        $user = new User();
        $user->name = $request->input('name');
        $user->paterno = $request->input('paterno');
        $user->materno = $request->input('materno');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        // $user->ctg_area_id = $request->input('area');
        // Guardar otros campos del usuario si los tienes

        // Guardar el usuario en la base de datos
        $user->save();

        // Asignar roles al usuario si se seleccionaron roles en el formulario
        if ($request->filled('roles')) {
            // $user->assignRole($request->input('roles'));
            $user->roles()->sync($request->input('roles'));
        }
        return redirect()->route('user.index')->with('success', 'Usuario creado exitosamente.');
    }
    public function actualizarusuario(User $user)
    {
        $areas = Ctg_Area::all();
        $roles = Role::all();
        return view('usuarios.usuarioactualiza', compact('areas', 'roles', 'user'));
    }


    public function updated(Request $request, User $user)
    {
        $validate = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'paterno' => ['required', 'string', 'max:255'],
            'materno' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'roles' => ['array'],
        ]);
        // dd($request->input('roles'));
        $user->name = $request->input('name');
        $user->paterno = $request->input('paterno');
        $user->materno = $request->input('materno');
        $user->email = $request->input('email');
        $user->ctg_area_id = $request->input('area');
        // Guardar otros campos del usuario si los tienes

        // Guardar el usuario en la base de datos
        $user->update();

        // Asignar roles al usuario si se seleccionaron roles en el formulario
        if ($request->filled('roles')) {
            $user->roles()->sync($request->input('roles'));
        }
        return redirect()->route('user.index')->with('success', 'Usuario editado exitosamente.');
    }

    public function delete(User $user)
    {
        $user->status_user = 0;
        $user->update();
        return redirect()->route('user.index')->with('success', 'El usuario se dio de baja exitosamente.');
    }

    public function reactivar(User $user)
    {
        $user->status_user = 1;
        $user->update();
        return redirect()->route('user.index')->with('success', 'El usuario se reactivo exitosamente.');
    }

    
    public function password_view(User $user)
    {
        

        return view('usuarios.usuarios-password', compact('user'));
    }

    public function password(Request $request,User $user)
    {
        $validate = $this->validate($request, [ 
            'password' => ['required', 'string', 'max:255'],
        ]);
        // dd($request->input('roles'));
        $user->password = Hash::make($request->input('password'));
        $user->update();
        return redirect()->route('user.index')->with('success', 'La contraseña se actualizo exitosamente.');
    }
}
