@extends('adminlte::page')


@section('title', 'Empleados Activos')

@section('content_header')
    <h1 class="ml-3">Recursos Humanos-Empleados Activos</h1>
@stop
@section('content')
<x-alert />
    <div class="container-fluid">
        @livewire('rh.empleados-activos')
    </div>
@stop
