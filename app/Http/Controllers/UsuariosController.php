<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
        return view('usuarios.usuariosindex');
    }
    public function nuevousuario()
    {
        return view('usuarios.usuarionuevo');
    }
    public function actualizarusuario()
    {
        return view('usuarios.usuarioactualiza');
    }

    
}
