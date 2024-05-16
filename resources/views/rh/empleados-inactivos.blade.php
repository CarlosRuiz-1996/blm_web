@extends('adminlte::page')


@section('title', 'Empleados Inactivos')

@section('content_header')
    <h1 class="ml-3">Recursos Humanos-Empleados Inactivos</h1>
@stop
@section('content')
<x-alert />
    <div class="container-fluid">
        @livewire('rh.empleados-inactivos')
    </div>
@stop
