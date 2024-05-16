<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RhController extends Controller
{
    public function index(){
       
        return view('rh.rhindex');
    }

    public function altaempleado(){
       
        return view('rh.altaempleado');
    }
    public function indexVacaciones(){
       
        return view('rh.vacaciones');
    }
    public function solicitudVacaciones(){
       
        return view('rh.solicitud-vacaciones');
    }
    public function EmpleadosActivos(){
       
        return view('rh.empleados-activos');
    }
    public function EmpleadosInactivos(){
       
        return view('rh.empleados-inactivos');
    }
    public function EmpleadosPerfil($id){
       
        return view('rh.empleados-perfil')->with('id', $id);
    }
}
