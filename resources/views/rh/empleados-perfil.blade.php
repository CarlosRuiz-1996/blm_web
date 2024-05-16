@extends('adminlte::page')


@section('title', 'Perfil Empleado')

@section('content_header')
    <h1 class="ml-3">Recursos Humanos-Perfil Empleado</h1>
@stop
@section('content')
<x-alert />
    <div class="container-fluid">
        @livewire('rh.empleados-perfil', ['id' => $id])
    </div>
@stop
