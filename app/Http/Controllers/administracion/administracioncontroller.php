<?php

namespace App\Http\Controllers\administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class administracioncontroller extends Controller
{
    public function index(){
        return view('adminstracion.DashboardAdmin');
    }
}
