<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemorandumController extends Controller
{
    public function create($factibilidad){
        return view('memorandum.memorandum-create',compact('factibilidad'));
    }
    public function validacion($memorandum){
        return view('memorandum.memorandum-validacion',compact('memorandum'));
    }
}
